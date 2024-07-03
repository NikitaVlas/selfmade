<?php
namespace Avency\Customerdashboard\Site\Importer;

use Avency\Customerdashboard\Site\Domain\Model\Customer;
use Avency\Customerdashboard\Site\Domain\Repository\CustomerRepository;
use Avency\Customerdashboard\Site\Services\CustomerService;
use Avency\Neos\Importer\Importer\AbstractImporter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;

class CustomerImporter extends AbstractImporter
{
    /**
     * @Flow\Inject
     * @var CustomerService
     */
    protected CustomerService $customerService;

    /**
     * @Flow\Inject
     * @var CustomerRepository
     */
    protected CustomerRepository $customerRepository;

    protected array $usedCustomerIds = [];

    /**
     * @param array $batch
     */
    public function import(array $batch)
    {
        if ($batch['deleted'] !== 0) {
            return;
        }

        $this->customerService->addOrUpdateCustomer(
            $batch['identifier'],
            $batch['kundenname'],
            $batch['projektkuerzel'],
            $batch['customernumber'],
            $batch['contactPersonId']
        );

        $this->usedCustomerIds[] = $batch['identifier'];
    }

    /**
     * Delete unused customers
     *
     * ToDo: call this function after import
     *
     * @return void
     * @throws IllegalObjectTypeException
     */
    public function cleanup(): void
    {
        $customers = $this->customerRepository->findAll();
        /** @var Customer $customer */
        foreach ($customers as $customer) {
            if (!in_array($customer->getTroiIdKunde(), $this->usedCustomerIds)) {
                $this->customerRepository->remove($customer);
            }
        }
    }
}
