<?php
namespace Botamp\Botamp\Resource;

class Me extends Resource{
  public function get() {
    return $this->botamp->me->get();
  }
}
