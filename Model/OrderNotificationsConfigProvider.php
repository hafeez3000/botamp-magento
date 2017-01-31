<?php
namespace Botamp\Botamp\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class OrderNotificationsConfigProvider implements ConfigProviderInterface
{

    private $me;
    private $configHelper;
    private $sessionHelper;

    public function __construct(
        \Botamp\Botamp\ApiResource\Me $me,
        \Botamp\Botamp\Helper\ConfigHelper $configHelper,
        \Botamp\Botamp\Helper\SessionHelper $sessionHelper
    ) {
        $this->me = $me;
        $this->configHelper = $configHelper;
        $this->sessionHelper = $sessionHelper;
    }

    public function getConfig()
    {
        $pageAttributes = $this->me->get()->getBody()['data']['attributes'];
        $contactRef = uniqid("botamp_{$_SERVER['HTTP_HOST']}_", true);

        $this->sessionHelper->getSessionObject('checkout')->setBotampContactRef($contactRef);

        return [
        'orderNotificationsEnabled' => $this->configHelper->orderNotificationsEnabled(),
            'botampPageAttributes' => [
                'appId' => $pageAttributes['facebook_app_id'],
                'pageId' => $pageAttributes['facebook_id'],
                'ref' => $contactRef,
            ]
        ];
    }
}
