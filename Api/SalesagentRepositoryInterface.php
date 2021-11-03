<?php

namespace AHT\Salesagents\Api;

interface SalesagentRepositoryInterface
{
    /**
     * Undocumented function
     *
     * @param \AHT\Salesagents\Api\Data\SalesagentInterface $saleagent
     * @return \AHT\Salesagents\Api\Data\SalesagentInterface
     */
    public function save(\AHT\Salesagents\Api\Data\SalesagentInterface $saleagent);

    /**
     * Undocument function
     *
     * @param int $saleagentId
     * @return \AHT\Salesagents\Api\Data\SalesagentInterface
     */
    public function getById($saleagentId);

    /**
     * Undocumented function
     *
     * @param \AHT\Salesagents\Api\Data\SalesagentInterface $saleagent
     * @return \AHT\Salesagents\Api\Data\SalesagentInterface
     */
    public function delete(\AHT\Salesagents\Api\Data\SalesagentInterface $saleagent);

    /**
     * Undocument function
     *
     * @param int $saleagentId
     * @return \AHT\Salesagents\Api\Data\SalesagentInterface
     */
    public function deleteById($saleagentId);
}
