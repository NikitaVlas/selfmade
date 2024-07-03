<?php

namespace Avency\Customerdashboard\Site\Services;

use Avency\Customerdashboard\Site\Domain\Model\ContactPerson;
use Avency\Customerdashboard\Site\Domain\Model\Customer;
use Avency\Customerdashboard\Site\Domain\Model\Person;
use Avency\Customerdashboard\Site\Domain\Repository\ContactPersonRepository;
use Avency\Customerdashboard\Site\Domain\Repository\CustomerRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Persistence\QueryResultInterface;

/**
 * CustomerService
 */
class CustomerService
{
    /**
     * @Flow\Inject
     * @var CustomerRepository
     */
    protected CustomerRepository $customerRepository;

    /**
     * @Flow\Inject
     * @var TroiCustomerService
     */
    protected TroiCustomerService $troiCustomerService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected PersistenceManagerInterface $persistenceManager;

    /**
     * @Flow\Inject
     * @var ContactPersonRepository
     */
    protected ContactPersonRepository $contactPersonRepository;

    /**
     * @Flow\Inject
     * @var ContactPersonService
     */
    protected ContactPersonService $contactPersonService;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getCustomers(int $offset = 0, int $limit = 100, string $title = null): array
    {
        if ($title) {
            $customers = $this->customerRepository->findAllByTitle($title);
        } else {
            $customers = $this->customerRepository->findAll()->getQuery();
        }
        $count = $customers->count();

        $results = [];
        foreach ($customers->setLimit($limit)->setOffset($offset)->execute() as $customer) {
            $contactPersonLastName = null;
            $productManagerFirsName = "";
            $productManagerLastName = "";

            if ($customer->getContactPerson()) {
                $contactPersonLastName = $customer->getContactPerson()->getLastName();
            }

            if ($customer->getProductManagerDefault()) {
                $productManagerFirsName = $customer->getProductManagerDefault()->getFirstName();
            }

            if ($customer->getProductManagerDefault()) {
                $productManagerLastName = $customer->getProductManagerDefault()->getLastName();
            }

            $results[] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($customer),
                'title' => $customer->getTitle(),
                'abbreviation' => $customer->getAbbreviation(),
                'customerNumber' => $customer->getCustomerNumber(),
                'priority' => $customer->getPriority(),
                'productManagerDefault' => [
                    'firstName' => $productManagerFirsName,
                    'lastName' => $productManagerLastName,
                ],
                'contactPerson' => [
                    'lastname' => $contactPersonLastName,
                ]
            ];
        }

        return ["items" => $results, "count" => $count];
    }

    /**
     * @param Customer $customer
     * @return array
     */
    public function getCustomer(Customer $customer)
    {
        $results = [
            'identifier' => $this->persistenceManager->getIdentifierByObject($customer),
            'title' => $customer->getTitle(),
            'abbreviation' => $customer->getAbbreviation(),
            'customerNumber' => $customer->getCustomerNumber(),
            'priority' => $customer->getPriority(),
            'contactPerson' => [],
            'productManagerDefault' => null
        ];
        if ($customer->getContactPerson() instanceof ContactPerson) {
            $results['contactPerson'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($customer->getContactPerson()),
                'firstname' => $customer->getContactPerson()->getFirstName(),
                'lastname' => $customer->getContactPerson()->getLastName(),
                'email' => $customer->getContactPerson()->getEmail(),
                'phone' => $customer->getContactPerson()->getPhone()
            ];
        }

        if ($customer->getProductManagerDefault() instanceof Person) {
            $results['productManagerDefault'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($customer->getProductManagerDefault()),
                'firstname' => $customer->getProductManagerDefault()->getFirstName(),
                'lastname' => $customer->getProductManagerDefault()->getLastName(),
            ];
        }

        return $results;
    }

    public function updateCustomer(Customer $customer, ?int $priority = null, ?Person $productManagerDefault = null)
    {
        $customer->setProductManagerDefault($productManagerDefault);
        $customer->setPriority($priority);
        $this->customerRepository->update($customer);

        $results = [
            'priority' => $priority,
            'productManagerDefault' => null
        ];

        if ($productManagerDefault instanceof Person) {
            $results['productManagerDefault'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($customer->getProductManagerDefault()),
                'firstname' => $customer->getProductManagerDefault()->getFirstName(),
                'lastname' => $customer->getProductManagerDefault()->getLastName(),
            ];
        }

        return $results;
    }

    /**
     * Add a new customer or update the existing one
     *
     * @param string $troiIdKunde
     * @param string $kundenname
     * @param string|null $projektKuerzel
     * @param int|null $customerNumber
     * @param string|null $contactPersonId
     * @return Customer
     * @throws IllegalObjectTypeException
     */
    public function addOrUpdateCustomer(string $troiIdKunde, string $kundenname, ?string $projektKuerzel = null, ?int $customerNumber = null, ?string $contactPersonId = null): Customer
    {
        $defaultAbbreviation = "";
        $defaultCustomerNumber = 0;

        $customer = $this->customerRepository->findOneByTroiIdKunde($troiIdKunde);
        $newCustomer = false;

        if (!$customer instanceof Customer) {
            $customer = new Customer();
            $newCustomer = true;
        }

        $customer->setTroiIdKunde($troiIdKunde);
        $customer->setTitle($kundenname);
        $customer->setAbbreviation($projektKuerzel ?? $defaultAbbreviation);
        $customer->setCustomerNumber($customerNumber ?? $defaultCustomerNumber);

        if ($contactPersonId) {
            $contactPerson = $this->contactPersonRepository->findByTroiContactPersonId($contactPersonId);
            if (!$contactPerson instanceof ContactPerson) {
                $contactPerson = $this->contactPersonService->createContactPersonFromTroiId($contactPersonId);
            }

            if ($contactPerson instanceof ContactPerson) {
                $customer->setContactPerson($contactPerson);
            }
        }

        if ($newCustomer) {
            $this->customerRepository->add($customer);
        } else {
            $this->customerRepository->update($customer);
        }

        return $customer;
    }

    public function getUsedSystemByCutomer(Customer $customer)
    {
        $useSystems = $customer->getUsedSystems();
        $result = [];

        foreach ($useSystems as $useSystem) {
            $result[] = [
                'title' => $useSystem->getTitle(),
                'usedVersion' => $useSystem->getUsedVersion(),
                'cookie' => $useSystem->getCookie(),
                'trackingsTools' => $useSystem->getTrackingsTools(),
                'sslCertificate' => $useSystem->getSslCertificate(),
                'urlLocal' => $useSystem->getUrlLocal(),
                'urlPreview' => $useSystem->getUrlPreview(),
                'urlLive' => $useSystem->getUrlLive(),
                'productManager' =>$useSystem->getProductManager(),
                'leadDev' => $useSystem->getLeaDev(),
            ];
        }

        return $result;
    }

    public function createCustomerFromTroiId(string $customerId): Customer
    {
        $customerRawData = $this->troiCustomerService->getTroiCustomerById($customerId);
        $customer = $this->addOrUpdateCustomer(
            $customerRawData['identifier'],
            $customerRawData['kundenname'],
            $customerRawData['projektkuerzel'],
            $customerRawData['customernumber']
        );

        $this->persistenceManager->persistAll();

        return $customer;
    }
}
