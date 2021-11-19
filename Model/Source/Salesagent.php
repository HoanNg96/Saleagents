<?php

namespace AHT\Salesagents\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Salesagent extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource implements OptionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customer;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Customer $customers
    ) {
        $this->_customerFactory = $customerFactory;
        $this->_customer = $customers;
    }

    /**
     * Get all select options
     *
     * @return array|null
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $collection = $this->_customerFactory->create()->getCollection()->addFieldToFilter('is_sales_agent', 1);
            $this->_options[] = ['label' => '-- Choose a Sale Agent --', 'value' => null];
            foreach ($collection as $item) {
                $this->_options[] = [
                    'label' => ' ' . $item->getFirstname() . ' ' . $item->getMiddlename() . ' ' . $item->getLastname(),
                    'value' => $item->getEntityId(),
                ];
            }
        }

        return $this->_options;
    }
}
