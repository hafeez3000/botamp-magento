<?php
namespace Botamp\Botamp\ApiResource;

class Contact extends AbstractApiResource
{
    public function get($contactRef)
    {
        try {
            return $this->botamp->contacts->get($contactRef);
        } catch (\Botamp\Exceptions\NotFound $e) {
            return null;
        }
    }
}
