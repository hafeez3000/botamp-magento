<?php
namespace Botamp\Botamp\ApiResource;

class OrderEntity extends AbstractApiResource
{

    private $orderHelper;
    private $sessionHelper;
    private $subscription;
    private $contact;

    public function __construct(
        \Botamp\Botamp\Helper\OrderHelper $orderHelper,
        \Botamp\Botamp\Helper\SessionHelper $sessionHelper,
        \Botamp\Botamp\ApiResource\Subscription $subscription,
        \Botamp\Botamp\ApiResource\Contact $contact,
        \Botamp\Botamp\Helper\ConfigHelper $configHelper
    ) {
    
        parent::__construct($configHelper);
        $this->orderHelper = $orderHelper;
        $this->sessionHelper = $sessionHelper;
        $this->subscription = $subscription;
        $this->contact = $contact;
    }

    public function create($order)
    {
        if (($contact = $this->getContact()) !== null) {
            $attributes = $this->getAttributes($order);
            $entity = $this->botamp->entities->create($attributes);

            $subscription = $this->subscription->create($entity, $contact);
            $order->setBotampSubscriptionId($subscription->getBody()['data']['id']);
            $order->save();
            return $entity;
        }
    }

    public function update($order)
    {
        if ($order->getBotampSubscriptionId() !== null &&
        ($order->getState() !== $order->getOrigData('state'))) {
            $attributes = $this->getAttributes($order);
            return $this->botamp->entities->update($this->getBotampEntityId($order), $attributes);
        }
    }

    private function getContact()
    {
        $contactRef = $this->sessionHelper->getSessionObject('checkout')->getBotampContactRef();
        return $this->contact->get($contactRef);
    }

    private function getBotampEntityId($order)
    {
        $subscriptionId =  $order->getBotampSubscriptionId();
        $subscription = $this->subscription->get($subscriptionId);

        return $subscription->getBody()['data']['attributes']['entity_id'];
    }

    private function getAttributes($order)
    {
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
