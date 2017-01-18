<?php
namespace Botamp\Botamp\Observer;

class AfterOrderSave implements \Magento\Framework\Event\ObserverInterface {

  protected $orderEntity;
  protected $orderFactory;
  protected $configHelper;

  public function __construct(
    \Botamp\Botamp\Resource\OrderEntity $orderEntity,
    \Magento\Sales\Model\OrderFactory $orderFactory,
    \Botamp\Botamp\Helper\ConfigHelper $configHelper
  ) {
    $this->orderEntity = $orderEntity;
    $this->orderFactory = $orderFactory;
    $this->configHelper = $configHelper;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    if(!$this->configHelper->orderNotificationsEnabled())
      return;

    $eventName = $observer->getEvent()->getName();

    if($eventName == 'sales_order_save_after') {
      $order = $observer->getEvent()->getOrder();
      $this->orderEntity->update($order);
    }
    elseif($eventName == 'checkout_onepage_controller_success_action') {
      $orderIds = $observer->getEvent()->getOrderIds();
      if(count($orderIds)) {
        $order = $this->orderFactory->create()->load($orderIds[0]);
        $this->orderEntity->create($order);
      }
    }
  }
}
