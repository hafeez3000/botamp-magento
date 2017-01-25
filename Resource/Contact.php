<?php
namespace Botamp\Botamp\Resource;

class Contact extends Resource{

  public function __construct() {
    parent::__construct();
  }

  public function get($contactRef) {
    try {
			return $this->botamp->contacts->get($contactRef);
		} catch (\Botamp\Exceptions\NotFound $e) {
			return null;
		}
  }
}
