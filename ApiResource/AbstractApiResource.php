<?php
namespace Botamp\Botamp\ApiResource;

abstract class AbstractApiResource
{
    //@codingStandardsIgnoreStart
    protected $botamp;

    public function __construct(\Botamp\Botamp\Helper\ConfigHelper $configHelper) {
        $this->botamp = new \Botamp\Client($configHelper->getApiKey());

        if (($apiBase = $configHelper->getApiBase()) !== null)
            $this->botamp->setApiBase($apiBase);
    }
    //@codingStandardsIgnoreEnd
}
