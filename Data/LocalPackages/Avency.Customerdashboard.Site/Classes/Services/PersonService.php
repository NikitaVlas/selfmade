<?php
namespace Avency\Customerdashboard\Site\Services;

use Avency\Customerdashboard\Site\Domain\Model\Person;
use Avency\Customerdashboard\Site\Domain\Repository\PersonRepository;
use Neos\Flow\Annotations as Flow;

/**
 * CustomerService
 */
class PersonService
{
    /**
     * @Flow\Inject
     * @var PersonRepository
     */
    protected PersonRepository $personRepository;

    /**
     * @Flow\Inject
     * @var TroiMitarbeiterService
     */
    protected TroiMitarbeiterService $troiMitarbeiterService;

    /**
     * Get all person as an array
     *
     * @return array
     */
    public function getPersons(): array
    {
        return $this->personRepository->findAll()->toArray();
    }

    public function addOrUpdatePerson(string $troiIdPerson, string $firstName, string $lastName, ?string $abbreviationPerson = null): Person
    {
        $defaultAbbreviation = "";

        $person = $this->personRepository->findOneByTroiIdPerson($troiIdPerson);
        $newPerson = false;

        if (!$person instanceof Person) {
            $person = new Person();
            $newPerson = true;
        }

        $person->setTroiIdPerson($troiIdPerson);
        $person->setFirstName($firstName);
        $person->setLastName($lastName);
        $person->setAbbreviationPerson($abbreviationPerson ?? $defaultAbbreviation);

        if ($newPerson) {
            $this->personRepository->add($person);
        } else {
            $this->personRepository->update($person);
        }

        return $person;
    }

    public function createPersonFromTroiId(string $troiIdPerson): Person
    {
        $personRawData = $this->troiMitarbeiterService->getTroiPersonById($troiIdPerson);
        $person = $this->addOrUpdatePerson(
            $personRawData['identifier'],
            $personRawData['vorname'],
            $personRawData['nachname'],
            $personRawData['kurzzeichen']
        );

        return $person;
    }
}
