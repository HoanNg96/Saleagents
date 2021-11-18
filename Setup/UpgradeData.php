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

            $attributeCode1 = 'sale_agent_id';
            $attributeCode2 = 'commission_type';
            $attributeCode3 = 'commission_value';

            $attributeGroupName = 'Sales Agent';

            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);

            $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

            // Add attributes to attribute group
            foreach ($attributeSetIds as $attributeSetId) {
                $eavSetup->addAttributeGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $attributeGroupName,
                    200
                );

                // add attribute to group
                $categorySetup->addAttributeToGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $attributeGroupName,
                    $attributeCode1,
                    null
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

            $GroupName = 'Sales Agent';

            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);

            $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

            // update attribute group sort_order
            foreach ($attributeSetIds as $attributeSetId) {
                $eavSetup->updateAttributeGroup(
                    $entityTypeId,
                    $attributeSetId,
                    $GroupName,
                    'sort_order',
                    '17'
                );
            }
        }
        
        $setup->endSetup();
    }
}
