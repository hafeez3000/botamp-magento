<?php
namespace Botamp\Botamp\Resource;

class Entity extends Resource{

  protected $logger;

  public function __construct(\Psr\Log\LoggerInterface $logger) {
    parent::__construct();
    $this->logger = $logger;
  }

  public function createOrUpdate($product) {
    $attributes = [
      'title' => $product->getName(),
      'description' => $product->getDescription(),
      'url' => $product->getUrlModel()->getUrl($product),
      'entity_type' => 'product',
      // 'image_url' => $this->getProductImageUrl($product),
    ];

    if(($botamp_entity_id = $product->getData('botamp_entity_id')) !== null) {
      $this->botamp->entities->update($botamp_entity_id, $attributes);
      return;
    }

    $entity = $this->botamp->entities->create($attributes);
    $product->setData('botamp_entity_id', $entity->getBody()['data']['id']);
  }

  public function delete($product) {
    if(($botamp_entity_id = $product->getData('botamp_entity_id')) !== null)
      $this->botamp->entities->delete($botamp_entity_id);
  }

  protected function getProductImageUrl($product) {
    $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
    $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
    return $imagehelper->init($product,'product_base_image')->getUrl();
  }
}
