<?php
namespace Botamp\Botamp\Utils;

class Notifier {

  private $messageManager;
  private $messageFactory;
  private $backendSession;
  private $configHelper;
  private $messageTexts;

  public function __construct(
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Magento\Framework\Message\Factory $messageFactory,
    \Magento\Backend\Model\Session $backendSession,
    \Botamp\Botamp\Helper\ConfigHelper $configHelper
  ) {
    $this->messageManager = $messageManager;
    $this->messageFactory = $messageFactory;
    $this->backendSession = $backendSession;
    $this->configHelper = $configHelper;

    $this->messageTexts = [
      'module_not_setup' => __('Please complete the Botamp extension installation on the <a href="%1">settings page</a>.',"#"),
      'api_key_not_working' => __('Authentication with the provided API key is not working.<br/>
                                   Please provide a valid API key on the <a href="%1">settings page</a>.',"#")
    ];
  }

  public function showWarningMessages() {
    $messages = [];
    $apiKey = $this->configHelper->getApiKey();
    if($apiKey === null || empty($apiKey)) {
      $messages[] = $this->createWarningMessage('module_not_setup');
    }
    elseif($this->backendSession->getBotampAuthStatus() === 'unauthorized') {
      $messages[] = $this->createWarningMessage('api_key_not_working');
    }
    $this->messageManager->addUniqueMessages($messages);
  }

  private function createWarningMessage($type) {
    return $this->messageFactory->create(
      \Magento\Framework\Message\MessageInterface::TYPE_WARNING,
      $this->messageTexts[$type]
    );
  }
}
