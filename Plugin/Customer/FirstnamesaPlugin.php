<?php

namespace AHT\Salesagents\Plugin\Customer;

class FirstnamesaPlugin
{
    public function afterGetFirstname(\Magento\Customer\Model\Data\Customer $subject, $result)
    {
        /* add Sales Agent to before customer first name */
        if ($subject->getCustomAttribute("is_sales_agent")) {
            if ($subject->getCustomAttribute("is_sales_agent")->getValue() == \Magento\Eav\Model\Entity\Attribute\Source\Boolean::VALUE_YES) {
                if (!str_contains($result, 'Sales Agent: ')) {
                    $result = "Sales Agent: " . $result;
                }
            } else {
                $result = preg_replace("/Sales Agent: /", "", $result);
            }
        }
        return $result;
    }
}
