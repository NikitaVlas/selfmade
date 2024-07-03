<?php
namespace Avency\Customerdashboard\Site\DataProvider;

use Avency\Customerdashboard\Site\Services\CustomerService;
use Avency\Customerdashboard\Site\Services\TroiCustomerService;
use Avency\Neos\Importer\DataProvider\AbstractDataProvider;
use Neos\Flow\Annotations as Flow;

class CustomerDataProvider extends AbstractDataProvider
{
    /**
     * @Flow\Inject
     * @var TroiCustomerService
     */
    protected TroiCustomerService $troiCustomerService;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetch(int $offset = null, int $limit = null)
    {
        return $this->troiCustomerService->getTroiCustomers($offset, $limit);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->troiCustomerService->getTroiCustomers());
    }
}
