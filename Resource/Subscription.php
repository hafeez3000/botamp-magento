<?php
namespace Botamp\Botamp\Resource;

class Subscription extends Resource {

  public function create( $entity, $contact ) {
		$attributes = [
			'entity_id' => $entity->getBody()['data']['id'],
			'subscription_type' => $entity->getBody()['data']['attributes']['entity_type'],
			'contact_id' => $contact->getBody()['data']['id'],
		];

		return $this->botamp->subscriptions->create($attributes);
	}

  public function get($subscriptionId) {
    return $this->botamp->subscriptions->get($subscriptionId);
  }

  public function delete($subscriptionId) {
    return $this->botamp->subscriptions->delete($subscriptionId);
  }
}
