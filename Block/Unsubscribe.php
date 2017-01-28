<?php
namespace Botamp\Botamp\Block;

class Unsubscribe extends \Magento\Backend\Block\Template {

  protected $order;

  public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Framework\Registry $registry
  ) {
    parent::__construct($context, []);
    $this->order = $registry->registry('current_order');
  }

  public function canUnsubscribe() {
    return $this->order->getBotampSubscriptionId() !== null;
  }

  public function getUnsubscribeUrl() {
    return $this->getUrl('botamp/order/unsubscribe', ['id' => $this->order->getId()]);
  }
}
