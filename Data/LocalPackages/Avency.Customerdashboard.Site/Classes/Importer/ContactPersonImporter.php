<?php
namespace Avency\Customerdashboard\Site\Importer;


use Avency\Customerdashboard\Site\Domain\Model\ContactPerson;
use Avency\Customerdashboard\Site\Domain\Repository\ContactPersonRepository;
use Avency\Customerdashboard\Site\Services\ContactPersonService;
use Avency\Neos\Importer\Importer\AbstractImporter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;

class ContactPersonImporter extends AbstractImporter
{
    /**
     * @Flow\Inject
     * @var ContactPersonService
     */
    protected ContactPersonService $contactPersonService;

    /**
     * @Flow\Inject
     * @var ContactPersonRepository
     */
    protected ContactPersonRepository $contactPersonRepository;

    protected array $usedContactPersonsIds = [];

    /**
     * @param array $batch
     */
    public function import(array $batch)
    {
        if ($batch['deleted'] !== 0) {
            return;
        }

        $this->contactPersonService->addOrUpdateContactPerson(
            $batch['identifier'],
            $batch['vorname'],
            $batch['nachname'],
            $batch['email'],
            $batch['telefon']
        );

        $this->usedContactPersonsIds[] = $batch['identifier'];
    }

    /**
     * Delete unused contactPerson
     *
     * ToDo: call this function after import
     *
     * @return void
     * @throws IllegalObjectTypeException
     */
    public function cleanup(): void
    {
        $contactPersons = $this->contactPersonRepository->findAll();
        /** @var ContactPerson $contactPerson */
        foreach ($contactPersons as $contactPerson) {
            if (!in_array($contactPerson->getTroiContactPersonId(), $this->usedContactPersonsIds)) {
                $this->contactPersonRepository->remove($contactPerson);
            }
        }
    }
}
