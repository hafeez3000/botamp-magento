<?php
namespace Botamp\Botamp\Resource;

class OrderEntity extends Resource {

  protected $checkoutSession;
  protected $orderHelper;

  public function __construct(
    \Magento\Checkout\Model\Session $checkoutSession,
    \Botamp\Botamp\Helper\OrderHelper $orderHelper
  ) {
    parent::__construct();
    $this->checkoutSession = $checkoutSession;
    $this->orderHelper = $orderHelper;
  }

  public function create($order) {
    if(($contact = $this->getContact()) !== null) {
      $attributes = $this->getAttributes($order);
      $entity = $this->botamp->entities->create($attributes);

      $subscription = (new Subscription())->create($entity, $contact);
      $order->setBotampSubscriptionId($subscription->getBody()['data']['id']);
      $order->save();
      return $entity;
    }
  }

  public function update($order) {
    if($order->getBotampSubscriptionId() !== null &&
      ($order->getState() !== $order->getOrigData('state'))) {
      $attributes = $this->getAttributes($order);
      return $this->botamp->entities->update($this->getBotampEntityId($order), $attributes);
    }
  }

  public function delete($order) {
    if($order->getBotampSubscriptionId() !== null)
    $this->botamp->entities->delete($this->getBotampEntityId($order));
  }

  protected function getContact() {
    $contactRef = $this->checkoutSession->getBotampContactRef();
    return (new Contact())->get($contactRef);
  }

  protected function getBotampEntityId($order) {
    $subscriptionId =  $order->getBotampSubscriptionId();
    $subscription = (new Subscription())->get($subscriptionId);

    return $subscription->getBody()['data']['attributes']['entity_id'];
  }

  protected function getAttributes($order) {
    $orderMeta = $this->orderHelper->getOrderMeta($order);
    return [
			'title' => $orderMeta['order_number'] . ' - ' . $orderMeta['recipient_name'],
			'url' => $orderMeta['order_url'],
			'entity_type' => 'order',
			'status' => $order->getState(),
			'meta' => $orderMeta
		];
  }
}
