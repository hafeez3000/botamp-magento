<?php
namespace Botamp\Botamp\Observer;

class AfterOrderPlace implements \Magento\Framework\Event\ObserverInterface {

  protected $entity;
  protected $orderFactory;
  protected $configHelper;

  public function __construct(
    \Botamp\Botamp\Resource\Entity $entity,
    \Magento\Sales\Model\OrderFactory $orderFactory,
    \Botamp\Botamp\Helper\ConfigHelper $configHelper
  ) {
    $this->entity = $entity;
    $this->orderFactory = $orderFactory;
    $this->configHelper = $configHelper;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    if(!$this->configHelper->orderNotificationsEnabled())
      return;

    $orderIds = $observer->getEvent()->getOrderIds();
    if(count($orderIds)) {
      $order = $this->orderFactory->create()->load($orderIds[0]);
      $this->entity->createOrUpdate($order);
    }
  }
}
