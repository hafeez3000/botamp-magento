<?php
namespace Botamp\Botamp\Observer;

class AfterOrderSaveObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{

    private $orderFactory;
    private $configHelper;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Botamp\Botamp\Helper\ConfigHelper $configHelper,
        \Botamp\Botamp\Utils\ResourceProxy $resourceProxy
    ) {
        $this->orderFactory = $orderFactory;
        $this->configHelper = $configHelper;
        parent::__construct($resourceProxy);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->configHelper->orderNotificationsEnabled()) {
            return;
        }

        $this->resourceProxy->setCurrentResource('order_entity');

        $eventName = $observer->getEvent()->getName();

        if ($eventName == 'sales_order_save_after') {
            $order = $observer->getEvent()->getOrder();
            $this->resourceProxy->update($order);
        } elseif ($eventName == 'checkout_onepage_controller_success_action') {
            $orderIds = $observer->getEvent()->getOrderIds();
            if (!empty($orderIds)) {
                $order = $this->orderFactory->create()->load($orderIds[0]);
                $this->resourceProxy->create($order);
            }
        }
    }
}
