<?php
namespace Botamp\Botamp\Observer;

class AfterConfigChangeObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface {

  protected $notifier;

  public function __construct(
    \Botamp\Botamp\Resource\Me $me,
    \Botamp\Botamp\Utils\Notifier $notifier
  ) {
    parent::__construct($me);
    $this->notifier = $notifier;
  }

  // @codingStandardsIgnoreStart
  public function execute(\Magento\Framework\Event\Observer $observer) {
    // @codingStandardsIgnoreEnd
    $this->resourceProxy->get();
    $this->notifier->showWarningMessages();
  }
}
