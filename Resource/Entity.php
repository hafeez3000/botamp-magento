<?php
namespace Botamp\Botamp\Resource;

use Botamp\Botamp\Helper\OrderHelper;

class Entity extends Resource{
  protected $objectManager;

  public function __construct() {
    parent::__construct();
    $this->objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
  }

  public function createOrUpdate($object) {
    return $this->isCreated($object) ? $this->update($object) : $this->create($object);
  }

  protected function create($object) {
    $entityType = $this->getObjectEntityType($object);
    if($entityType == 'product')
      return $this->createProductEntity($object);
    elseif($entityType == 'order')
      return $this->createOrderEntity($object);
  }

  protected function createProductEntity($product) {
    $attributes = $this->getAttributes($product);
    $entity = $this->botamp->entities->create($attributes);
    $product->setBotampEntityId($entity->getBody()['data']['id']);

    return $entity;
  }

  protected function createOrderEntity($order) {
    $contactRef = $this->checkoutSession->getBotampContactRef();
    $contact = (new Contact())->get($contactRef);
    if($contact === null)
      return;

    $attributes = $this->getAttributes($order);
    $entity = $this->botamp->entities->create($attributes);

    $subscription = (new Subscription())->create($entity, $contact);
    $order->setBotampSubscriptionId($subscription->getBody()['data']['id']);
    $order->save();

    return $entity;
  }

  protected function update($object) {
    $entityType = $this->getObjectEntityType($object);
    if($entityType == 'product')
      return $this->updateProductEntity($object);
    elseif($entityType == 'order')
      return $this->updateOrderEntity($object);
  }

  protected function updateProductEntity($product) {
    $attributes = $this->getAttributes($product);
    $entityId = $product->getBotampEntityId();

    return $this->botamp->entities->update($entityId, $attributes);
  }

  protected function updateOrderEntity($order) {
    if($order->getState() == $order->getOrigData('state'))
      return;

    $attributes = $this->getAttributes($order);

    return $this->botamp->entities->update($this->getOrderBotampEntityId($order), $attributes);
  }

  public function delete($object) {
    if(!$this->isCreated($object))
      return;

    if($entityType == 'product')
      $this->botamp->entities->delete($object->getBotampEntityId());
    elseif($entityType == 'order')
      $this->botamp->entities->delete($this->getOrderBotampEntityId($object));
  }

  public function importAllProducts() {
    $factoryClassPath = 'Magento\Catalog\Model\ResourceModel\Product\CollectionFactory';
    $collectionFactory = $this->objectManager->create($factoryClassPath);

    $products = $collectionFactory->create()->addAttributeToSelect('*')->load();

    foreach($products as $product)
      if(!$this->isCreated($product))
        $this->create($product);
  }

  protected function isCreated($object) {
    $entityType = $this->getObjectEntityType($object);
    if($entityType == 'product')
      return $object->getBotampEntityId() !== null;
    elseif($entityType == 'order')
      return $object->getBotampSubscriptionId() !== null;
  }

  protected function getOrderBotampEntityId($order) {
    $subscriptionId =  $order->getBotampSubscriptionId();
    $subscription = (new Subscription())->get($subscriptionId);

    return $subscription->getBody()['data']['attributes']['entity_id'];
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
      $orderMeta = (new OrderHelper($object, $this->objectManager))->getOrderMeta();

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
}
