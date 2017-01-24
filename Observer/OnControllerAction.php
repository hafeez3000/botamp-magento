<?php
namespace Botamp\Botamp\Observer;

class OnControllerAction implements \Magento\Framework\Event\ObserverInterface {

  private $messageManager;
  private $messageFactory;
  private $backendHelper;
  private $configHelper;

  public function __construct(
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Magento\Framework\Message\Factory $messageFactory,
    \Magento\Backend\Helper\Data $backendHelper,
    \Botamp\Botamp\Helper\ConfigHelper $configHelper
  ) {
    $this->messageManager = $messageManager;
    $this->messageFactory = $messageFactory;
    $this->backendHelper = $backendHelper;
    $this->configHelper = $configHelper;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $apiKey = $this->configHelper->getApiKey();
    if(!($apiKey === null || empty($apiKey)))
      return;

    $message = $this->messageFactory->create(
      \Magento\Framework\Message\MessageInterface::TYPE_WARNING,
      __(
        'Please complete the Botamp plugin installation on the <a href="%1">settings page</a>.',
        $this->backendHelper->getUrl('admin/system_config/index')
      )
    );

    $this->messageManager->addUniqueMessages([$message]);
  }
}
