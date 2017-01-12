<?php
namespace Botamp\Botamp\Block\Adminhtml;

use Magento\Backend\Block\Template;

class CustomBlock extends Template
{
  public function __construct(Template\Context $context) {
    parent::__construct($context, []);
  }
}
