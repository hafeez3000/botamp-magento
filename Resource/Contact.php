<?php
namespace Botamp\Botamp\Resource;

class Contact extends Resource{

  public function get($contactRef) {
    try {
			return $this->botamp->contacts->get($contactRef);
		} catch (\Botamp\Exceptions\NotFound $e) {
			return null;
		}
  }
}
