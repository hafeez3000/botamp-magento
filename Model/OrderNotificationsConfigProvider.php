<?php
namespace Botamp\Botamp\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class OrderNotificationsConfigProvider implements ConfigProviderInterface {

  protected $me;
  protected $checkoutSession;

  public function __construct(
    \Botamp\Botamp\Resource\Me $me,
    \Magento\Checkout\Model\Session $checkoutSession
  ) {
    $this->me = $me;
    $this->checkoutSession = $checkoutSession;
  }

  public function getConfig() {
    $pageAttributes = $this->me->get()->getBody()['data']['attributes'];
    $orderRef = uniqid("botamp_{$_SERVER['HTTP_HOST']}_", true);

    $this->checkoutSession->setOrderRef($orderRef);

    return [
      'botampPageAttributes' => [
        'appId' => $pageAttributes['facebook_app_id'],
        'pageId' => $pageAttributes['facebook_id'],
        'ref' => $orderRef
      ]
    ];
  }
}
