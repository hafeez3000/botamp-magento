<?php
namespace Botamp\Botamp\Resource;

class Entity extends Resource{
  protected $logger;
  protected $objectManager;

  public function __construct(\Psr\Log\LoggerInterface $logger) {
    parent::__construct();
    $this->objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
    $this->logger = $logger;
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
    $entityId = $this->getBotampEntityId($object);

    $entity = $this->botamp->entities->update($entityId, $attributes);
  }

  public function delete($object) {
    if($this->isCreated($object))
      $this->botamp->entities->delete($this->getBotampEntityId($object));
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
    return $this->getBotampEntityId($object) !== null;
  }

  protected function getBotampEntityId($object){
    return $object->getData('botamp_entity_id');
  }

  protected function setBotampEntityId($object, $entityId) {
    $object->setData('botamp_entity_id', $entityId);
  }

  protected function getAttributes($object) {
    if ($this->getClassName($object) == 'product') {
      return [
        'title' => $object->getName(),
        'description' => $object->getDescription(),
        'url' => $object->getUrlModel()->getUrl($object),
        'entity_type' => 'product',
        // 'image_url' => $this->getProductImageUrl($object),
      ];
    }
  }

  protected function getClassName($object) {
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
