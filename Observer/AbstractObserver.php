<?php
namespace Botamp\Botamp\Observer;

abstract class AbstractObserver
{
    //@codingStandardsIgnoreStart
    protected $resourceProxy;
    //@codingStandardsIgnoreEnd

    public function __construct(\Botamp\Botamp\Utils\ResourceProxy $resourceProxy)
    {
        $this->resourceProxy = $resourceProxy;
    }
}
