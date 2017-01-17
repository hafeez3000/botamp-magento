<?php
namespace Botamp\Botamp\Resource;

class Entity extends Resource{
  protected $objectManager;

  public function __construct() {
    parent::__construct();
    $this->objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
  }

  public function createOrUpdate($object) {
    $this->isCreated($object) ? $this->update($object) : $this->create($object);
  }

  protected function create($object) {
    $attributes = $this->getAttributes($object);

    $entity = $this->botamp->entities->create($attributes);

    $this->setBotampEntityId($object, $entity->getBody()['data']['id']);
  }

  protected function update($object) {
    $attributes = $this->getAttributes($object);
    $entityId = $object->getBotampEntityId();

    $entity = $this->botamp->entities->update($entityId, $attributes);
  }

  public function delete($object) {
    if($this->isCreated($object))
      $this->botamp->entities->delete($object->getBotampEntityId());
  }

  public function importAllProducts() {
    $factoryClassPath = 'Magento\Catalog\Model\ResourceModel\Product\CollectionFactory';
    $collectionFactory = $this->objectManager->create($factoryClassPath);

    $products = $collectionFactory->create()->addAttributeToSelect('*')->load();

    foreach($products as $product) {
      if(!$this->isCreated($product)) {
        $this->create($product);
      }
    }
  }

  protected function isCreated($object) {
    return $object->getBotampEntityId() !== null;
  }

  protected function setBotampEntityId($object, $entityId) {
    $object->setBotampEntityId($entityId);
    if($this->getObjectEntityType($object) === 'order')
      $object->save();
  }

  protected function getAttributes($object) {
    $entityType = $this->getObjectEntityType($object);

    if($entityType == 'product') {
      return [
        'title' => $object->getName(),
        'description' => $object->getDescription(),
        'url' => $object->getUrlModel()->getUrl($object),
        'entity_type' => 'product',
        // 'image_url' => $this->getProductImageUrl($object),
      ];
    }
    elseif($entityType == 'order') {
      $orderMeta = $this->getOrderMeta($object);

      return [
  			'title' => $orderMeta['order_number'] . ' - ' . $orderMeta['recipient_name'],
  			'url' => $orderMeta['order_url'],
  			'entity_type' => 'order',
  			'status' => $object->getState(),
  			'meta' => $orderMeta
  		];
    }
  }

  protected function getObjectEntityType($object) {
    $className = get_class($object);
    if(($position = strpos($className, "\Interceptor")) !== false)
      $className = substr($className, 0, $position);
    $className = substr(strrchr($className, "\\"), 1);

    return strtolower($className);
  }

  protected function getProductImageUrl($product) {
    $imagehelper = $this->objectManager->create('Magento\Catalog\Helper\Image');
    return $imagehelper->init($product,'product_base_image')->getUrl();
  }

  protected function getOrderMeta($order) {
    $address = $order->getShippingAddress() === null ? $order->getBillingAddress() : $order->getShippingAddress();

    return [
			'recipient_name' => $order->getCustomerName(),
			'order_number' => $order->getRealOrderId(),
			'currency' => $order->getOrderCurrencyCode(),
			'payment_method' => $order->getPayment()->getMethodInstance()->getTitle(),
			'order_url' => $this->getOrderFrontendUrl($order),
			'timestamp' => strtotime($order->getCreatedAt()),
			'address' => [
				'street_1' => $address->getStreet()[0],
				'street_2' => $address->getStreet()[0],
				'city' => $address->getCity(),
				'postal_code' => $address->getPostcode(),
				'state' => $address->getRegion(),
				'country' => $address->getCountryId(),
			],
			'elements' => $this->getOrderElements($order),
			'summary' => [
				'subtotal' => $order->getSubtotal(),
				'shipping_cost' => $order->getShippingAmount(),
				'total_tax' => $order->getTaxAmount(),
				'total_cost' => $order->getGrandTotal(),
			],
			'adjustments' => $this->getOrderAdjustments($order)
		];
  }

  protected function getOrderElements($order) {
    $elements = [];
    foreach($order->getAllItems() as $item) {
      $elements[] = [
        'title' => $item->getName(),
				'subtitle' => '',
				'quantity' => $item->getQtyOrdered(),
				'price' => $item->getPrice(),
				'currency' => $order->getOrderCurrencyCode(),
				// 'image_url' => $this->getProductImageUrl($item->getProduct()),
      ];
    }

    return $elements;
  }

  protected function getOrderAdjustments($order) {
    $adjustments = [];
    if(($adjustment = $order->getAdjustmentNegative()) !== null) {
      $adjustments[] = [
        'name' => 'Negative Adjustment',
        'amount' => $adjustment
      ];
    }
    if(($adjustment = $order->getAdjustmentPositive()) !== null) {
      $adjustments[] = [
        'name' => 'Positive Adjustment',
        'amount' => $adjustment
      ];
    }

    return $adjustments;
  }

  protected function getOrderFrontendUrl($order) {
    $urlModel = $this->objectManager->create('Magento\Framework\Url');
    return $urlModel->getUrl('sales/order/view', ['order_id' => $order->getId()]);
  }
}
