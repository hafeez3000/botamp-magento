<?php
namespace Botamp\Botamp\Resource;

class Me extends Resource{

  public function __construct() {
    parent::__construct();
  }

  public function get() {
    return $this->botamp->me->get();
  }
}
