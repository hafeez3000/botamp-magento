<?php
namespace Botamp\Botamp\Controller\Order;

class Unsubscribe extends \Magento\Framework\App\Action\Action {

  protected $resultPageFactory;
  protected $orderFactory;
  protected $resourceProxy;

  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
    \Magento\Sales\Model\OrderFactory $orderFactory,
    \Botamp\Botamp\Resource\Subscription $subscription) {
    $this->resultPageFactory = $resultPageFactory;
    $this->orderFactory = $orderFactory;
    $this->resourceProxy = new \Botamp\Botamp\Utils\ResourceProxy($subscription);
    return parent::__construct($context);
  }

  public function execute() {
    $orderId = $this->getRequest()->getParam('id');
    $order = $this->orderFactory->create()->load($orderId);

    $this->resourceProxy->delete($order->getBotampSubscriptionId());
    $order->setBotampSubscriptionId(null);
    $order->save();

    return $this->resultPageFactory->create();
  }
}
