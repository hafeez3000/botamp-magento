<?php
namespace Botamp\Botamp\Observer;

class AfterOrderSave implements \Magento\Framework\Event\ObserverInterface {

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

    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $logger = $objectManager->create('\Psr\Log\LoggerInterface');

    $eventName = $observer->getEvent()->getName();

    if($eventName === 'sales_order_save_after') {
      $order = $observer->getEvent()->getOrder();
      if($order->getState() !== $order->getOrigData('state')) {
        $this->entity->createOrUpdate($order);
      }
    }
    elseif($eventName === 'checkout_onepage_controller_success_action') {
      $orderIds = $observer->getEvent()->getOrderIds();
      if(count($orderIds)) {
        $order = $this->orderFactory->create()->load($orderIds[0]);
        $this->entity->createOrUpdate($order);
      }
    }
  }
}
