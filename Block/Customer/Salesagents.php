<?php

namespace AHT\Salesagents\Block\Customer;

class Salesagents extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_customerSession;
    protected $_resource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\ResourceConnection $Resource,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->_resource = $Resource;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getProductCollection()
    {
        $customerId = $this->_customerSession->getCustomer()->getId();
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*')->addFieldToFilter('sale_agent_id', $customerId);
        $aht_sales_agent = $this->_resource->getTableName('aht_sales_agent');
        $collection->getSelect()->group('e.entity_id')/* ->join(
            ['order_sa' => $aht_sales_agent],
            'e.entity_id = order_sa.order_item_id'
        ) */;/* use group for select distinct */
        $collection->setPageSize(5);
        /* $collection->printlogquery(true); */ /* print sql query */
        return $collection;
    }

    public function getEmptyOrdersMessage()
    {
        return "None product was assigned to you!";
    }
}
