<?php

namespace Avency\Customerdashboard\Site\Services;

use Avency\Customerdashboard\Site\Domain\Model\Customer;
use Avency\Customerdashboard\Site\Domain\Model\Person;
use Avency\Customerdashboard\Site\Domain\Model\Project;
use Avency\Customerdashboard\Site\Domain\Repository\CustomerRepository;
use Avency\Customerdashboard\Site\Domain\Repository\PersonRepository;
use Avency\Customerdashboard\Site\Domain\Repository\ProjectRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;

/**
 * CustomerService
 */
class ProjectService
{
    /**
     * @Flow\Inject
     * @var ProjectRepository
     */
    protected ProjectRepository $projectRepository;

    /**
     * @Flow\Inject
     * @var CustomerRepository
     */
    protected CustomerRepository $customerRepository;

    /**
     * @Flow\Inject
     * @var CustomerService
     */
    protected CustomerService $customerService;

    /**
     * @Flow\Inject
     * @var PersonRepository
     */
    protected PersonRepository $personRepository;

    /**
     * @Flow\Inject
     * @var PersonService
     */
    protected PersonService $personService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected PersistenceManagerInterface $persistenceManager;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getProjects(int $offset = 0, int $limit = 100,): array
    {
        $projects = $this->projectRepository->findAll()->getQuery()->setLimit($limit)->setOffset($offset)->execute()->toArray();

        $results = [];
        foreach ($projects as $project) {
            $customerName = "";
            $abbreviationPerson = "";

            if ($project->getCustomer() instanceof Customer) {
                $customerName = $project->getCustomer()->getTitle();
            }

            if ($project->getProjectLeader()) {
                $abbreviationPerson = $project->getProjectLeader()->getAbbreviationPerson();
            }

            $results[] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($project),
                'projectName' => $project->getProjectName(),
                'projectNumber' => $project->getProjectNumber(),
                'customer' => [
                    'title' => $customerName
                ],
                'projectLeader' => [
                    'abbreviationPerson' => $abbreviationPerson
                ]
            ];
        }
        return $results;
    }

    public function addOrUpdateProject(string $projectID, string $projectName, string $projectNumber, string $customerId, ?string $troiIdPerson): void
    {
        $project = $this->projectRepository->findOneByProjectID($projectID);
        $newProject = false;

        if (!$project instanceof Project) {
            $project = new Project();
            $newProject = true;
        }

        $project->setProjectID($projectID);
        $project->setProjectName($projectName);
        $project->setProjectNumber($projectNumber);

        $customer = $this->customerRepository->findByTroiIdKunde($customerId);
        if (!$customer instanceof Customer) {
            $customer = $this->customerService->createCustomerFromTroiId($customerId);
        }

        if ($customer instanceof Customer) {
            $project->setCustomer($customer);
        }

        if ($troiIdPerson) {
            $person = $this->personRepository->findByTroiIdPerson($troiIdPerson);
            if (!$person instanceof Person) {
                $person = $this->personService->createPersonFromTroiId($troiIdPerson);
            }

            if ($person instanceof Person) {
                $project->setProjectLeader($person);
            }
        }

        if ($newProject) {
            $this->projectRepository->add($project);
        } else {
            $this->projectRepository->update($project);
        }
    }
}
