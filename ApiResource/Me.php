<?php
namespace Botamp\Botamp\ApiResource;

class Me extends AbstractApiResource
{
    public function get()
    {
        return $this->botamp->me->get();
    }
}
