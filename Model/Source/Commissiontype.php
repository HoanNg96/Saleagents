<?php

namespace AHT\Salesagents\Model\Source;

class Commissiontype extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * Get all select options
     *
     * @return array|null
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('-- Fixed/Percent --'), 'value' => ''],
                ['label' => __('Fixed'), 'value' => 1],
                ['label' => __('Percent'), 'value' => 2]
            ];
        }
        return $this->_options;
    }
}
