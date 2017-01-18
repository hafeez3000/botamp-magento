<?php
namespace Botamp\Botamp\Resource;

class ProductEntity extends Resource {

  protected $productHelper;

  public function __construct(\Botamp\Botamp\Helper\ProductHelper $productHelper) {
    parent::__construct();
    $this->productHelper = $productHelper;
  }

  public function createOrUpdate($product) {
    return $product->getBotampEntityId() === null ? $this->create($product) : $this->update($product);
  }

  public function create($product) {
    $attributes = $this->getAttributes($product);
    $entity = $this->botamp->entities->create($attributes);
    $product->setBotampEntityId($entity->getBody()['data']['id']);
    return $entity;
  }

  public function update($product) {
    if($product->getBotampEntityId() !== null) {
      $attributes = $this->getAttributes($product);
      return $this->botamp->entities->update($product->getBotampEntityId(), $attributes);
    }
  }

  public function delete($product) {
    if($product->getBotampEntityId() !== null)
      $this->botamp->entities->delete($product->getBotampEntityId());
  }

  public function importAllProducts() {
    $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
    $factoryClassPath = 'Magento\Catalog\Model\ResourceModel\Product\CollectionFactory';
    $collectionFactory = $objectManager->create($factoryClassPath);
    $products = $collectionFactory->create()->addAttributeToSelect('*')->load();
    foreach($products as $product)
      if(!$this->isCreated($product))
        $this->create($product);
  }

  protected function getAttributes($product) {
    return [
      'title' => $product->getName(),
      'description' => $product->getDescription(),
      'url' => $product->getUrlModel()->getUrl($product),
      'entity_type' => 'product',
      // 'image_url' => $this->productHelper->getProductImageUrl($product),
    ];
  }
}
