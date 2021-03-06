<?php

namespace AHT\Salesagents\Model\ResourceModel\Salesagent;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'aht_salesagents_salesagent_collection';
    protected $_eventObject = 'salesagent_collection';

    /**
     * Define the resource model & the model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('AHT\Salesagents\Model\Salesagent', 'AHT\Salesagents\Model\ResourceModel\Salesagent');
    }
}
