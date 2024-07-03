<?php
namespace Avency\Customerdashboard\Site\Services;

use Avency\Customerdashboard\Site\Domain\Model\ContactPerson;
use Avency\Customerdashboard\Site\Domain\Repository\ContactPersonRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;

/**
 * CustomerService
 */
class ContactPersonService
{
    /**
     * @Flow\Inject
     * @var ContactPersonRepository
     */
    protected ContactPersonRepository $contactPersonRepository;

    /**
     * @Flow\Inject
     * @var TroiKontakteService
     */
    protected TroiKontakteService $troiKontakteService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected PersistenceManagerInterface $persistenceManager;

    /**
     * Get all person as an array
     *
     * @return array
     */
    public function getContactPersons(): array
    {
        return $this->contactPersonRepository->findAll()->toArray();
    }

    /**
     * @param string $troiContactPersonId
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $email
     * @param string|null $phone
     * @return ContactPerson
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function addOrUpdateContactPerson(string $troiContactPersonId, ?string $firstName, ?string $lastName, ?string $email, ?string $phone): ContactPerson
    {
        $defaultFirstName = "";
        $defaultLastName = "";
        $defaultEmail = "";
        $defaultPhone = "";

        $contactPerson = $this->contactPersonRepository->findOneByTroiContactPersonId($troiContactPersonId);
        $newContactPerson = false;

        if (!$contactPerson instanceof ContactPerson) {
            $contactPerson = new ContactPerson();
            $newContactPerson = true;
        }

        $contactPerson->setTroiContactPersonId($troiContactPersonId);
        $contactPerson->setFirstName($firstName ?? $defaultFirstName);
        $contactPerson->setLastName($lastName ?? $defaultLastName);
        $contactPerson->setEmail($email ?? $defaultEmail);
        $contactPerson->setPhone($phone ?? $defaultPhone);

        if ($newContactPerson) {
            $this->contactPersonRepository->add($contactPerson);
        } else {
            $this->contactPersonRepository->update($contactPerson);
        }

        return $contactPerson;
    }

    public function createContactPersonFromTroiId(string $contactPersonId): ContactPerson
    {
        $contactPersonRawData = $this->troiKontakteService->getTroiContactPersonById($contactPersonId);
        $contactPerson = $this->addOrUpdateContactPerson(
            $contactPersonRawData['identifier'],
            $contactPersonRawData['vorname'],
            $contactPersonRawData['nachname'],
            $contactPersonRawData['email'],
            $contactPersonRawData['telefon']
        );

        $this->persistenceManager->persistAll();

        return $contactPerson;
    }
}
