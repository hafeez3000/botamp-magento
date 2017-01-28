<?php

namespace Botamp\Botamp\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {
    protected $eavSetupFactory;
    protected $salesSetupFactory;

    public function __construct(
      EavSetupFactory $eavSetupFactory,
      SalesSetupFactory $salesSetupFactory) {
      $this->eavSetupFactory = $eavSetupFactory;
      $this->salesSetupFactory = $salesSetupFactory;
    }
    // @codingStandardsIgnoreStart
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
      // @codingStandardsIgnoreStart
      $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'botamp_entity_id',
        [
          'type' => 'int',
          'backend' => '',
          'frontend' => '',
          'label' => 'Botamp Entity ID',
          'input' => '',
          'class' => '',
          'source' => '',
          'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
          'visible' => false,
          'required' => false,
          'user_defined' => false,
          'default' => 0,
          'searchable' => false,
          'filterable' => false,
          'comparable' => false,
          'visible_on_front' => false,
          'used_in_product_listing' => false,
          'unique' => true,
          'apply_to' => ''
        ]
      );

      $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);
      $salesSetup->addAttribute('order', 'botamp_subscription_id', ['type' => 'int']);
    }
}
