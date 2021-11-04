<?php

namespace AHT\Salesagents\Setup;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;

    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.1') < 0) {

            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

            /**
             * Create a custom attribute group in all attribute sets
             * And, Add attribute to that attribute group for all attribute sets
             */

            // we are going to add those attribute to all attribute sets
            $attributeCode1 = 'sale_agent_id';
            $attributeCode2 = 'commission_type';
            $attributeCode3 = 'commission_value';

            // new custom attribute group name
            $attributeGroupName = 'Sales Agent';

            // get the catalog_product entity type id/code
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);

            // get default attribute set id (for add attribute group to 1 attribute set only)
            /* $attributeSetId = $categorySetup->getDefaultAttributeSetId(\Magento\Catalog\Model\Product::ENTITY); */
            // get attribute set id by name (for add attribute group to 1 attribute set only)
            /* $attributeSetId = $categorySetup->getAttributeSet($entityTypeId, 'Product Details'); */

            // get the attribute set ids of all the attribute sets present in your Magento store
            $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

            foreach ($attributeSetIds as $attributeSetId) {
                $eavSetup->addAttributeGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $attributeGroupName,
                    200 // sort order
                );

                // add attribute to group
                $categorySetup->addAttributeToGroup(
                    $entityTypeId, // can also use: \Magento\Catalog\Model\Product::ENTITY instead of $entityTypeId
                    $attributeSetId,
                    $attributeGroupName, // attribute group
                    $attributeCode1, // this is defined above as 'sale_agent_id'
                    null // sort order, can be integer value like 10 or 30, etc.
                );
                $categorySetup->addAttributeToGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $attributeGroupName,
                    $attributeCode2,
                    null
                );
                $categorySetup->addAttributeToGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $attributeGroupName,
                    $attributeCode3,
                    null
                );
            }
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.2') < 0) {

            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

            // attribute group want update
            $GroupName = 'Sales Agent';

            // get the catalog_product entity type id/code
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);

            // get the attribute set ids of all the attribute sets present in your Magento store
            $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

            foreach ($attributeSetIds as $attributeSetId) {
                $eavSetup->updateAttributeGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $GroupName,
                    'sort_order', /* column field to update in eav_attribute_group table*/
                    '17' /* value to update */
                );
            }
        }
        $setup->endSetup();
    }
}
