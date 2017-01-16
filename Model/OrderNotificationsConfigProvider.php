<?php
namespace Botamp\Botamp\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class OrderNotificationsConfigProvider implements ConfigProviderInterface {

  protected $me;

  public function __construct(\Botamp\Botamp\Resource\Me $me) {
    $this->me = $me;
  }

  public function getConfig() {
    $pageAttributes = $this->me->get()->getBody()['data']['attributes'];
    return [
      'botampPageAttributes' => [
        'appId' => $pageAttributes['facebook_app_id'],
        'pageId' => $pageAttributes['facebook_id'],
        'ref' => uniqid("botamp_{$_SERVER['HTTP_HOST']}_", true)
      ]
    ];
  }
}
