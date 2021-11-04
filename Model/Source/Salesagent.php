<?php

namespace AHT\Salesagents\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;


class Salesagent extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource implements OptionSourceInterface
{
    protected $optArray;

    protected $_customer;
    protected $_customerFactory;

    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Customer $customers
    ) {
        $this->_customerFactory = $customerFactory;
        $this->_customer = $customers;
    }

    /**
     * @return array|null
     * @return Post Category
     */
    public function getAllOptions()
    {
        if ($this->optArray === null) {
            /* get all customer is sale agent */
            $collection = $this->_customerFactory->create()->getCollection()->addFieldToFilter('is_sales_agent', 1);
            $this->optArray[] = ['label' => '-- Choose a Sale Agent --', 'value' => null];
            foreach ($collection as $item) {
                $this->optArray[] = [
                    /* label = customer full name */
                    'label' => ' ' . $item->getFirstname() . ' ' . $item->getMiddlename() . ' ' . $item->getLastname(),
                    /* value = customer id */
                    'value' => $item->getEntityId(),
                ];
            }
        }

        return $this->optArray;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->toOptionArray() as $item) {
            $array[$item['value']] = $item['label'];
        }
        return $array;
    }
}
