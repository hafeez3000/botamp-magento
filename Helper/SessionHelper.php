<?php
namespace Botamp\Botamp\Helper;

class SessionHelper
{
    private $sessionObjects;

    public function __construct(
        \Magento\Checkout\Model\Session\Proxy $checkoutSession,
        \Magento\Backend\Model\Session\Proxy $backendSession,
        \Magento\Backend\Model\Auth\Session\Proxy $authSession
    ) {

        $this->sessionObjects = [
            'checkout' => $checkoutSession,
            'backend' => $backendSession,
            'auth' => $authSession
        ];
    }

    public function getSessionObject($sessionObjectCode)
    {
        return $this->sessionObjects[$sessionObjectCode];
    }
}
