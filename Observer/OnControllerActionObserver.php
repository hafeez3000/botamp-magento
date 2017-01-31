<?php
namespace Botamp\Botamp\Observer;

class OnControllerActionObserver extends AbstractObserver implements \Magento\Framework\Event\ObserverInterface
{

    private $notifier;

    public function __construct(\Botamp\Botamp\Utils\Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $requestName = $observer->getRequest()->getFullActionName();
        //Do not handle notifications here if the request is adminhtml_system_config_save
        //Use instead the AfterConfigChangeObserver because there are cache issues
        if (strpos($requestName, 'adminhtml_system_config_save') === false) {
            $this->notifier->showWarningMessages();
        }
    }
}
