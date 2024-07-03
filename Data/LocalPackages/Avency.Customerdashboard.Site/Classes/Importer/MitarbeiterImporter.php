<?php
namespace Avency\Customerdashboard\Site\Importer;

use Avency\Customerdashboard\Site\Domain\Model\Person;
use Avency\Customerdashboard\Site\Domain\Repository\PersonRepository;
use Avency\Customerdashboard\Site\Services\PersonService;
use Avency\Neos\Importer\Importer\AbstractImporter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;

class MitarbeiterImporter extends AbstractImporter
{
    /**
     * @Flow\Inject
     * @var PersonService
     */
    protected PersonService $personService;

    /**
     * @Flow\Inject
     * @var PersonRepository
     */
    protected PersonRepository $personRepository;

    protected array $usedMitarbeiterIds = [];

    /**
     * @param array $batch
     */
    public function import(array $batch)
    {
        if ($batch['deleted'] !== 0) {
            return;
        }

        $this->personService->addOrUpdatePerson(
            $batch['identifier'],
            $batch['vorname'],
            $batch['nachname'],
            $batch['kurzzeichen']
        );

        $this->usedMitarbeiterIds[] = $batch['identifier'];
    }

    /**
     * Delete unused mitarbeiter
     *
     * ToDo: call this function after import
     *
     * @return void
     * @throws IllegalObjectTypeException
     */
    public function cleanup(): void
    {
        $mitarbeiter = $this->personRepository->findAll();
        /** @var Person $mitarbeiter */
        foreach ($mitarbeiter as $person) {
            if (!in_array($person->getTroiIdPerson(), $this->usedMitarbeiterIds)) {
                $this->personRepository->remove($person);
            }
        }
    }
}
