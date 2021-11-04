<?php

namespace AHT\Salesagents\Setup;

use Magento\Eav\Model\Config;
/* use for Eav entity */
use Magento\Eav\Setup\EavSetupFactory;
/* default for install data */
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

        /**
         * To add Eav attribute need 3 params
         * - entity type of attribute u want to add (entity_type_code:entity_type_id - in eav_entity_type table)
         * (customer:1, customer_address:2, catalog_category:3, catalog_product:4)
         * - attribute_code of attribute u want to add (saved in eav_attribute table)
         * - properties of attribute u want to add
         * (see full list of properties in eav_attribute and catalog/customer_eav_attribute database tables)
         * (Mapping by \Magento\'Catalog/Customer'\Model\ResourceModel\Setup\PropertyMapper)
         */
        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'sale_agent_id', [
            'group' => 'Product Details',/* group attribute in BE */
            'type' => 'text',/* decide what table the value was stored -> catalog_product_entity_'text' */
            'backend' => '',/* class associated with the attribute */
            'frontend' => '',/* class associated with the attribute */
            'sort_order' => 200,/* order in group attribute */
            'label' => 'Sales Agent',/* label rendered in FE and BE */
            'input' => 'select',/* input type in BE */
            'class' => '',/* css class */
            'source' => 'AHT\Salesagents\Model\Source\Salesagent',/* class associated with the attribute */
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,/* define the scope of attribute (store, website or global) */
            'visible' => true,/* visible in FE */
            'required' => false,/* require value to save? : yes/no */
            'user_defined' => false,/* system attribute = false, custom attribute = true */
            'default' => '',/* default value */
            'searchable' => false,/* search the catalog based on the value of this attribute */
            'filterable' => false,/* used as a filter control at the top of columns in the grid */
            'comparable' => false,/* include this attribute as a row in the Compare Products report */
            'visible_on_front' => false,/* show in more information tab FE */
            'used_in_product_listing' => true,/* appear in catalog listing FE */
            'apply_to' => ''/* only apply to these product type (simple, virtual, ...) */
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

        /* add attribute to form */
        $customerAttr = $this->eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'is_sales_agent');
        $customerAttr->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $customerAttr->save();
        $setup->endSetup();

        $dataNewsRows = [
            [
                'type_name' => 'Fixed',
            ],
            [
                'type_name' => 'Percent',
            ]
        ];
        $eavSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY, 'company_type');
        foreach ($dataNewsRows as $data) {

            $setup->getConnection()->insert($setup->getTable('commission_type'), $data);
        }
    }
}
