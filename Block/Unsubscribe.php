<?php
namespace Botamp\Botamp\Block;

class Unsubscribe extends \Magento\Backend\Block\Template
{
    private $registry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry
    ) {
    
        parent::__construct($context, []);
        $this->registry = $registry;
    }

    public function canUnsubscribe()
    {
        $order = $this->registry->registry('current_order');
        return $order->getBotampSubscriptionId() !== null;
    }

    public function getUnsubscribeUrl()
    {
        $order = $this->registry->registry('current_order');
        return $this->getUrl('botamp/order/unsubscribe', ['id' => $order->getId()]);
    }
}
