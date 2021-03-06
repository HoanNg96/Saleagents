<?php

namespace AHT\Salesagents\Setup;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * Eav setup factory
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Eav config model
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * Init
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        // add attribute
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'sale_agent_id', [
            'group' => 'Product Details',
            'type' => 'text',
            'backend' => '',
            'frontend' => '',
            'sort_order' => 200,
            'label' => 'Sales Agent',
            'input' => 'select',
            'class' => '',
            'source' => 'AHT\Salesagents\Model\Source\Salesagent',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'apply_to' => ''
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'commission_type', [
            'group' => 'Product Details',
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'sort_order' => 210,
            'label' => 'Commission Type',
            'input' => 'select',
            'class' => '',
            'source' => 'AHT\Salesagents\Model\Source\Commissiontype',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'apply_to' => ''
        ]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'commission_value', [
            'group' => 'Product Details',
            'type' => 'decimal',
            'backend' => '',
            'frontend' => '',
            'sort_order' => 220,
            'label' => 'Commission Value',
            'input' => 'text',
            'class' => '',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'unique' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'apply_to' => ''
        ]);

        $eavSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'is_sales_agent', [
            'type' => 'int',
            'backend' => '',
            'frontend' => '',
            'label' => 'Is sales agent',
            'input' => 'boolean',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'visible'      => true,
            'default' => '',
            'user_defined' => false,
            'position'     => 999,
            'required' => false,
            'system'       => 0
        ]);

        // add attribute to admin customer form
        $customerAttr = $this->eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'is_sales_agent');
        $customerAttr->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $customerAttr->save();

        // add data to commission_type table
        $dataNewsRows = [
            [
                'type_name' => 'Fixed',
            ],
            [
                'type_name' => 'Percent',
            ]
        ];
        foreach ($dataNewsRows as $data) {
            $setup->getConnection()->insert($setup->getTable('commission_type'), $data);
        }

        $setup->endSetup();
    }
}
