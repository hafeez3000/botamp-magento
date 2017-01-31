<?php
namespace Botamp\Botamp\Utils;

class Notifier
{
    private $messageManager;
    private $messageFactory;
    private $configHelper;
    private $sessionHelper;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Message\Factory $messageFactory,
        \Botamp\Botamp\Helper\ConfigHelper $configHelper,
        \Botamp\Botamp\Helper\SessionHelper $sessionHelper
    ) {
        $this->messageManager = $messageManager;
        $this->messageFactory = $messageFactory;
        $this->configHelper = $configHelper;
        $this->sessionHelper = $sessionHelper;
    }

    public function showWarningMessages()
    {
        $authSession = $this->sessionHelper->getSessionObject('auth');
        if (!$authSession->isLoggedIn()) {
            return;
        }

        $backendSession = $this->sessionHelper->getSessionObject('backend');

        $moduleNotSetupText =  __('Please complete the Botamp extension installation
                                   on the <a href="%1">settings page</a>.', "#");
        $apiKeyNotWorkingText = __('Authentication with the provided API key is not working.<br/>
                                    Please provide a valid API key on the <a href="%1">settings page</a>.', "#");

        $message = null;
        $apiKey = $this->configHelper->getApiKey();
        if ($apiKey === null || empty($apiKey)) {
            $message = $this->createWarningMessage($moduleNotSetupText);
        } elseif ($backendSession->getBotampAuthStatus() === 'unauthorized') {
            $message = $this->createWarningMessage($apiKeyNotWorkingText);
        }
        $this->messageManager->addUniqueMessages([$message]);
    }

    private function createWarningMessage($text)
    {
        return $this->messageFactory->create(
            \Magento\Framework\Message\MessageInterface::TYPE_WARNING,
            $text
        );
    }
}
