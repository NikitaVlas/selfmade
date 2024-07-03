<?php

namespace Avency\Customerdashboard\Site\Controller;

use Avency\Customerdashboard\Site\Domain\Model\Customer;
use Avency\Customerdashboard\Site\Domain\Model\Person;
use Avency\Customerdashboard\Site\Domain\Repository\CustomerRepository;
use Avency\Customerdashboard\Site\Services\CustomerService;
use Avency\Customerdashboard\Site\Services\PersonService;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\View\JsonView;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Annotations as Flow;

class CustomerController extends ActionController
{
    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     */
    protected $supportedMediaTypes = ['application/json'];

    /**
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @Flow\Inject
     * @var CustomerService
     */
    protected CustomerService $customerService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var PersonService
     */
    protected PersonService $personService;

    /**
     * Get all Customer
     *
     * @param int $offset
     * @param int $limit
     * @return void
     */
    public function getCustomersAction(int $offset = 0, int $limit = 100, string $title = null)
    {
        $this->view->assign('value', $this->customerService->getCustomers($offset, $limit, $title));
    }

    /**
     * Get one Customer
     *
     * @param Customer $customer
     * @return void
     */
    public function getCustomerAction(Customer $customer)
    {
        $results = $this->customerService->getCustomer($customer);
        $this->view->assign('value', $results);
    }

    /**
     * Update Customer
     *
     * @param Customer $customer
     * @param int|null $priority
     * @param Person|null $productManagerDefault
     * @return void
     */
    public function updateCustomerAction(Customer $customer, ?int $priority = null, ?Person $productManagerDefault = null)
    {
        $this->view->assign('value', $this->customerService->updateCustomer($customer, $priority, $productManagerDefault));
    }

    /**
     * Get all Person
     *
     * @return void
     */
    public function getAllPersonsAction()
    {
        $persons = $this->personService->getPersons();
        $results = [];

        /** @var Person $person */
        foreach ($persons as $person) {
            $results[] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($person),
                'firstName' => $person->getFirstName(),
                'lastName' => $person->getLastName(),
                'abbreviationPerson' => $person->getAbbreviationPerson()
            ];
        }

        $this->view->assign('value', $results);
    }

    public function getUsedSystemByCutomerAction(Customer $customer)
    {
        $this->view->assign('value', $this->customerService->getUsedSystemByCutomer($customer));
    }
}
