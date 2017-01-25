<?php
namespace Botamp\Botamp\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class OrderNotificationsConfigProvider implements ConfigProviderInterface {

  protected $me;
  protected $checkoutSession;
  protected $configHelper;

  public function __construct(
    \Botamp\Botamp\Resource\Me $me,
    \Magento\Checkout\Model\Session $checkoutSession,
    \Botamp\Botamp\Helper\ConfigHelper $configHelper
  ) {
    $this->me = $me;
    $this->checkoutSession = $checkoutSession;
    $this->configHelper = $configHelper;
  }

  public function getConfig() {
    $pageAttributes = $this->me->get()->getBody()['data']['attributes'];
    $contactRef = uniqid("botamp_{$_SERVER['HTTP_HOST']}_", true);

    $this->checkoutSession->setBotampContactRef($contactRef);

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
