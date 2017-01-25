<?php
namespace Botamp\Botamp\Observer;

class AfterConfigChangeObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface {

  private $notifier;

  public function __construct(
    \Botamp\Botamp\Resource\Me $me,
    \Botamp\Botamp\Utils\Notifier $notifier
  ) {
    parent::__construct($me);
    $this->notifier = $notifier;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $this->resourceProxy->get();
    $this->notifier->showWarningMessages();
  }
}
