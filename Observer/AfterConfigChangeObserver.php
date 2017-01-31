<?php
namespace Botamp\Botamp\Observer;

class AfterConfigChangeObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{

    private $notifier;

    public function __construct(
        \Botamp\Botamp\Utils\Notifier $notifier,
        \Botamp\Botamp\Utils\ResourceProxy $resourceProxy
    ) {
    
        $this->notifier = $notifier;
        parent::__construct($resourceProxy);
    }

    //@codingStandardsIgnoreStart
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //@codingStandardsIgnoreEnd
        $this->resourceProxy->setCurrentResource('me');
        $this->resourceProxy->get();
        $this->notifier->showWarningMessages();
    }
}
