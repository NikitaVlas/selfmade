<?php

namespace Avency\Neos\Importer\Service;

use Avency\Neos\Importer\Domain\Model\ImportData;
use Avency\Neos\Importer\Domain\Model\ImportVersion;
use Avency\Neos\Importer\Domain\Model\PresetPartDefinition;
use Avency\Neos\Importer\Domain\Repository\ImportDataRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\ConsoleOutput;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * @Flow\Scope("singleton")
 */
class ImportService
{
    /**
     * @Flow\Inject
     * @var ImportDataRepository
     */
    protected $importDataRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var EmailService
     */
    protected $emailService;

    /**
     * @Flow\Inject(name="Avency.Neos.Importer:NeosImporterLogger")
     * @var LoggerInterface
     */
    protected $neosImporterLogger;


    /**
     * Get Batch
     *
     * @param string $preset
     * @param string $partName
     * @param ImportVersion $importVersion
     * @param int|null $offset
     * @param int|null $limit
     * @return array
     */
    public function getImportDataIdentifier(string $preset, string $partName, ImportVersion $importVersion, int $offset = null, int $limit = null)
    {
        $findQuery = $this->importDataRepository->findByPresetNameAndPartNameAndImportVersion($preset, $partName, $importVersion)->getQuery();
        if ($offset !== null && $limit !== null) {
            $findQuery = $findQuery->setOffset($offset)->setLimit($limit);
        }

        $importDataIdentifier = [];
        foreach ($findQuery->execute() as $importData) {
            $importDataIdentifier[] = $this->persistenceManager->getIdentifierByObject($importData);
        }
        $this->persistenceManager->clearState();
        return $importDataIdentifier;
    }

    /**
     * Prepare batch
     *
     * @param PresetPartDefinition $presetPartDefinition
     * @return void
     */
    public function prepareBatch(PresetPartDefinition $presetPartDefinition, ConsoleOutput $output)
    {
        $importerClassName = $presetPartDefinition->getImporterClassName();
        $importer = $importerClassName::create($presetPartDefinition->getName(), $presetPartDefinition->getPart(), $output, $presetPartDefinition->getImporterOptions());
        $importer->prepareBatch();
    }

    /**
     * Import batch
     *
     * @param PresetPartDefinition $presetPartDefinition
     * @param ConsoleOutput $output
     * @param array $batch
     */
    public function importBatch(PresetPartDefinition $presetPartDefinition, ConsoleOutput $output, array $batch)
    {
        try {
            $importerClassName = $presetPartDefinition->getImporterClassName();
            $importer = $importerClassName::create($presetPartDefinition->getName(), $presetPartDefinition->getPart(), $output, $presetPartDefinition->getImporterOptions());
            $importer->prepare();

            $this->neosImporterLogger->notice('Remove import data directly: ' . ($presetPartDefinition->isRemoveImportDataDirectly() ? 'yes' : 'no'));

            foreach ($batch as $identifier => $data) {
                $this->neosImporterLogger->info('Startig import of ' . $identifier);
                $data = json_decode($data, true);
                if ($importer->checkData($data)) {
                    $importer->import($data);
                    $this->neosImporterLogger->info('Imported ' . $identifier . ' successfully.');
                } else {
                    $this->neosImporterLogger->warning('Importdata with id ' . $identifier . ' has no data. Skipping.');
                }

                if ($presetPartDefinition->isRemoveImportDataDirectly()) {
                    $importData = $this->importDataRepository->findByIdentifier($identifier);
                    if ($importData instanceof ImportData) {
                        $this->importDataRepository->remove($importData);
                        $this->neosImporterLogger->info('Removed import data of id ' . $identifier);
                        $this->persistenceManager->persistAll();
                    }
                }
            }
        } catch (\Exception $e) {
            $subject = 'Error in Importer on ' . getenv('FLOW_CONTEXT');
            $content = 'Error in Importer: ' . $presetPartDefinition->getImporterClassName() . '<br><br><hr><pre>' . $e->getTraceAsString() . '</pre><br><br><hr><pre>' . $e->getMessage() . '</pre>';

            $this->emailService->sendErrorMail($subject, $content);
            throw $e;
        }
    }
}
