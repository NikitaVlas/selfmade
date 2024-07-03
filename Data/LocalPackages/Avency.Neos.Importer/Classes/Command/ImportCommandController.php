<?php

namespace Avency\Neos\Importer\Command;

use Avency\Neos\Importer\Domain\Model\ImportVersion;
use Avency\Neos\Importer\Domain\Model\PresetPartDefinition;
use Avency\Neos\Importer\Domain\Repository\ImportDataRepository;
use Avency\Neos\Importer\Domain\Repository\ImportVersionRepository;
use Avency\Neos\Importer\Service\ImportService;
use Avency\Neos\Importer\Service\PrepareService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Core\Booting\Scripts;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Utility\Arrays;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Import Command Controller
 *
 * @Flow\Scope("singleton")
 */
class ImportCommandController extends CommandController
{
    /**
     * @Flow\Inject(name="Avency.Neos.Importer:NeosImporterLogger")
     * @var LoggerInterface
     */
    protected $neosImporterLogger;

    /**
     * @Flow\Inject
     * @var PrepareService
     */
    protected $prepareService;

    /**
     * @Flow\Inject
     * @var ImportService
     */
    protected $importService;

    /**
     * @Flow\Inject
     * @var ImportDataRepository
     */
    protected $importDataRepository;

    /**
     * @Flow\Inject
     * @var ImportVersionRepository
     */
    protected $importVersionRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\InjectConfiguration(package="Neos.Flow")
     * @var array
     */
    protected $flowSettings;

    /**
     * @Flow\InjectConfiguration(package="Avency.Neos.Importer")
     * @var array
     */
    protected $settings;

    /**
     * Initialize Object
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->output->getOutput()->getFormatter()->setStyle('warning', new OutputFormatterStyle('yellow'));
        $this->output->getOutput()->getFormatter()->setStyle('notice', new OutputFormatterStyle('blue'));
    }

    /**
     * Prepare ImportData
     *
     * @param string $preset Name of the preset which holds the configuration for the preparation
     * @param string|null $parts Optional comma separated names of parts. If no parts are specified, all parts will be prepared.
     * @param int|null $batchSize Number of records to prepare at a time for each part
     * @return void
     */
    public function prepareDataCommand(string $preset, string $parts = null, int $batchSize = null)
    {
        $this->outputLine('Prepare data for import.');
        $this->outputLine();

        $this->runMethodOnPresetParts($preset, function ($preset, $partName) use ($batchSize) {
            if ($batchSize === null && !empty($this->settings['presets'][$preset]['parts'][$partName]['dataProviderOptions']['defaultBatchSize'])) {
                $batchSize = $this->settings['presets'][$preset]['parts'][$partName]['dataProviderOptions']['defaultBatchSize'];
            }
            $i = 0;

            $importVersion = $this->importVersionRepository->findOneByPresetAndPartAndIsNotFinished($preset, $partName);

            $presetPartDefinition = $this->getPresetPartDefinition($preset, $partName);
            if ($importVersion instanceof ImportVersion && $presetPartDefinition->isSkipPrepareIfDataExists()) {
                if ($importVersion->skipPrepare()) {
                    $message = sprintf('The Preset "%s" / Part "%s" already has an import in progress which is not fully imported yet. Skipping this preset.', $preset, $partName);
                    $this->neosImporterLogger->warning($message);
                    $this->output->outputLine('<warning>' . $message . '</warning>');
                    return;
                }
            }

            $importVersion = new ImportVersion();
            $importVersion->setStatus(ImportVersion::STATUS_NEW);
            $importVersion->setPreset($preset);
            $importVersion->setPart($partName);
            $this->importVersionRepository->add($importVersion);
            $this->persistenceManager->persistAll();

            do {
                $arguments = [
                    'preset' => $preset,
                    'partName' => $partName,
                    'offset' => ($i * $batchSize),
                    'limit' => $batchSize,
                    'importVersion' => $this->persistenceManager->getIdentifierByObject($importVersion),
                ];

                ob_start();
                Scripts::executeCommand('avency.neos.importer:import:preparedatapart', $this->flowSettings, true, $arguments);
                $result = ob_get_contents();
                ob_end_clean();
                $i++;

                if ($result != '' && strpos(trim($result), 'Skipping this preset.')) {
                    $this->output->outputLine('<warning>' . $result . '</warning>');

                    $result = 'empty';
                }
            } while (trim($result) != 'empty');

            $importVersion->setStatus(ImportVersion::STATUS_READY_TO_IMPORT);
            $this->importVersionRepository->update($importVersion);
            $this->persistenceManager->persistAll();
        }, $parts);
    }

    /**
     * Prepare Data Part Command
     *
     * @param string $preset
     * @param string $partName
     * @param int|null $offset
     * @param int|null $limit
     * @param string|null $importVersion
     * @throws StopActionException
     */
    public function prepareDataPartCommand(string $preset, string $partName, int $offset = null, int $limit = null, ?string $importVersion = null)
    {
        $presetPartDefinition = $this->getPresetPartDefinition($preset, $partName);
        $doNotChangeToImportReadyStatus = !!$importVersion;
        $skipPrepareSkipCheck = false;

        if ($importVersion) {
            $importVersion = $this->persistenceManager->getObjectByIdentifier($importVersion, ImportVersion::class);
            $skipPrepareSkipCheck = true;
        }

        if (!$importVersion instanceof ImportVersion) {
            $importVersion = $this->importVersionRepository->findOneByPresetAndPartAndIsNotFinished($preset, $partName);
            $skipPrepareSkipCheck = false;
        }

        if (!$importVersion instanceof ImportVersion) {
            $importVersion = new ImportVersion();
            $importVersion->setStatus(ImportVersion::STATUS_NEW);
            $importVersion->setPreset($preset);
            $importVersion->setPart($partName);

            $this->importVersionRepository->add($importVersion);
            $this->persistenceManager->persistAll();
        }

        if ($presetPartDefinition->isSkipPrepareIfDataExists()) {
            if ($importVersion->skipPrepare() && !$skipPrepareSkipCheck) {
                $message = sprintf('The Preset "%s" / Part "%s" already has an import in progress which is not fully imported yet. Skipping this preset.', $preset, $partName);
                $this->neosImporterLogger->warning($message);
                $this->output->outputLine('<warning>' . $message . '</warning>');
                return;
            }
        }

        if (!$this->prepareService->prepareData($presetPartDefinition, $offset, $limit, $importVersion, $skipPrepareSkipCheck)) {
            $this->output->outputLine('<warning>empty</warning>');
        } else {
            $this->output->outputLine('<success>Successfully Imported</success>');
        }

        if (!$doNotChangeToImportReadyStatus) {
            $importVersion->setStatus(ImportVersion::STATUS_READY_TO_IMPORT);
            $this->importVersionRepository->update($importVersion);
            $this->persistenceManager->persistAll();
        }
    }

    /**
     * Cleanup the parts of a presets
     *
     * @param string $preset
     * @param string|null $parts
     * @return void
     * @throws StopActionException
     */
    public function cleanupDataCommand(string $preset, ?string $parts = null): void
    {
        $presetSettings = Arrays::getValueByPath($this->settings, ['presets', $preset]);
        if (!is_array($presetSettings)) {
            $this->outputLine('<error>' . (sprintf('Preset "%s" not found ...', $preset)) . '</error>');
            $this->quit(1);
        }
        if (empty($presetSettings['parts'])) {
            $this->outputLine('<error>' . (sprintf('No parts in Preset "%s" defined', $preset)) . '</error>');
            $this->quit(1);
        }

        $presetParts = array_keys($presetSettings['parts']);
        $parts = $parts ? Arrays::trimExplode(',', $parts) : $presetParts;

        $partDiffs = array_diff($parts, $presetParts);
        if (count($partDiffs) > 0) {
            $this->output->outputLine('<error>' . (sprintf('The preset "%s" does not have the parts: %s', $preset, implode(', ', $partDiffs))) . '</error>');
            return;
        }

        foreach ($parts as $partName) {
            $this->cleanupPresetParts($partName, $preset);
        }
    }

    /**
     * Cleanup a part of a presets
     *
     * @param string $preset
     * @param string $partName
     * @return void
     * @throws StopActionException
     */
    public function cleanupDataPartCommand(string $preset, string $partName): void
    {
        $presetSettings = Arrays::getValueByPath($this->settings, ['presets', $preset]);
        if (!is_array($presetSettings)) {
            $this->outputLine('<error>' . (sprintf('Preset "%s" not found ...', $preset)) . '</error>');
            $this->quit(1);
        }
        if (empty($presetSettings['parts'])) {
            $this->outputLine('<error>' . (sprintf('No parts in Preset "%s" defined', $preset)) . '</error>');
            $this->quit(1);
        }

        if (!key_exists($partName, $presetSettings['parts'])) {
            $this->output->outputLine('<error>' . (sprintf('The preset "%s" does not have the part: %s', $preset, $partName)) . '</error>');
            return;
        }

        $this->cleanupPresetParts($partName, $preset);
    }

    /**
     * Run Batch for preset
     *
     * @param string $preset Name of the preset which holds the configuration for the import
     * @param string|null $parts Optional comma separated names of parts. If no parts are specified, all parts will be imported.
     * @param int|null $batchSize Number of records to import at a time for each part
     * @return void
     */
    public function batchCommand(string $preset, string $parts = null, int $batchSize = null)
    {
        $this->outputLine('Start importing');
        $this->outputLine('');

        $this->runMethodOnPresetParts($preset, function ($preset, $partName) use ($batchSize) {
            if ($batchSize === null && !empty($this->settings['presets'][$preset]['parts'][$partName]['importerOptions']['defaultBatchSize'])) {
                $batchSize = $this->settings['presets'][$preset]['parts'][$partName]['importerOptions']['defaultBatchSize'];
            }

            $importVersion = $this->importVersionRepository->findOneByPresetAndPartAndIsReadyToImportOrInProcess($preset, $partName);

            if (!$importVersion instanceof ImportVersion) {
                $this->output->outputLine('<warning>There is no import version ready to import</warning>');
                $this->quit(1);
            }

            $presetPartDefinition = $this->getPresetPartDefinition($preset, $partName);

            if (($presetPartDefinition->isSkipPrepareBatchIfDataExists() && $importVersion->getStatus() !== ImportVersion::STATUS_IN_PROGRESS_IMPORT)
                || !$presetPartDefinition->isSkipPrepareBatchIfDataExists()) {
                // Update import version to prepare batch in progress
                if ($importVersion->getStatus() !== ImportVersion::STATUS_IN_PROGRESS_IMPORT) {
                    $importVersion->setStatus(ImportVersion::STATUS_IN_PROGRESS_PREPARE_BATCH);
                    $this->importVersionRepository->update($importVersion);
                    $this->persistenceManager->persistAll();
                }

                $this->output->outputLine('<notice>Preparing Batch...</notice>');
                $this->importService->prepareBatch($presetPartDefinition, $this->output);
            } else {
                $this->output->outputLine('<warning>Skipping Prepare Batch because the import was already started before.</warning>');
            }

            // Update import version to import in process
            if ($importVersion->getStatus() !== ImportVersion::STATUS_IN_PROGRESS_IMPORT) {
                $importVersion->setStatus(ImportVersion::STATUS_IN_PROGRESS_IMPORT);
                $this->importVersionRepository->update($importVersion);
                $this->persistenceManager->persistAll();
            }
            $this->output->outputLine('<notice>Importing Batch...</notice>');

            $currentBatch = 0;
            if ($batchSize !== null) {
                do {
                    $importDataIdentifier = $this->importService->getImportDataIdentifier(
                        $preset,
                        $partName,
                        $importVersion,
                        ($currentBatch * $batchSize),
                        $batchSize
                    );
                    $this->executeBatch($preset, $partName, $importDataIdentifier);
                    $currentBatch++;
                } while (!empty($importDataIdentifier));

                $this->cleanupPresetParts($partName, $preset);
                $this->persistenceManager->persistAll();
            } else {
                $importDataIdentifier = $this->importService->getImportDataIdentifier($preset, $partName, $importVersion);
                $this->executeBatch($preset, $partName, $importDataIdentifier);
            }

            // Update import version to finished
            $importVersion = $this->persistenceManager->getObjectByIdentifier($this->persistenceManager->getIdentifierByObject($importVersion), ImportVersion::class);
            $importVersion->setStatus(ImportVersion::STATUS_FINISHED);
            $this->importVersionRepository->update($importVersion);
            $this->persistenceManager->persistAll();
        }, $parts);
    }

    /**
     * Execute one import batch
     *
     * @param string $preset
     * @param array $importDataIdentifier
     * @param string $partName
     */
    protected function executeBatch(string $preset, string $partName, array $importDataIdentifier)
    {
        $importDatas = json_encode($importDataIdentifier);
        $arguments = [
            'preset' => $preset,
            'partName' => $partName,
            'batch' => $importDatas,
        ];

        $this->output->outputLine('<notice>Importing ' . count($importDataIdentifier) . ' Items.</notice> ' . $importDatas);

        Scripts::executeCommand('avency.neos.importer:import:executebatch', $this->flowSettings, true, $arguments);

        $this->output->outputLine('<success>Finished import of</success> ' . $importDatas);
    }

    /**
     * Command for executing one import batch
     *
     * @param string $preset
     * @param string $partName
     * @param string $batch
     */
    public function executeBatchCommand(string $preset, string $partName, string $batch)
    {
        $batchData = [];
        $importDataIdentifier = json_decode($batch, true);
        foreach ($importDataIdentifier as $identifier) {
            $importData = $this->importDataRepository->findByIdentifier($identifier);
            $batchData[$identifier] = $importData->getData();
        }

        $this->importService->importBatch($this->getPresetPartDefinition($preset, $partName), $this->output, $batchData);
    }

    /**
     * Get preset and part definition
     *
     * @param string $preset
     * @param string $partName
     * @return PresetPartDefinition
     * @throws StopActionException
     */
    protected function getPresetPartDefinition(string $preset, string $partName): PresetPartDefinition
    {
        $presetPartSettings = Arrays::getValueByPath($this->settings, ['presets', $preset, 'parts', $partName]);
        if (!is_array($presetPartSettings)) {
            $this->output->outputLine('<error>' . (sprintf('Preset "%s" and Part "%s" not found ...', $preset, $partName)) . '</error>');
            $this->quit(1);
        }

        $presetPartSettings['__currentPresetName'] = $preset;
        $presetPartSettings['__currentPresetPart'] = $partName;
        return new PresetPartDefinition($presetPartSettings);
    }

    /**
     * Run a method on each preset part
     *
     * @param string $preset
     * @param \Closure $method
     * @param string|null $parts
     */
    protected function runMethodOnPresetParts(string $preset, \Closure $method, string $parts = null)
    {
        $presetSettings = Arrays::getValueByPath($this->settings, ['presets', $preset]);
        if (!is_array($presetSettings)) {
            $this->outputLine('<error>' . (sprintf('Preset "%s" not found ...', $preset)) . '</error>');
            $this->quit(1);
        }
        if (empty($presetSettings['parts'])) {
            $this->outputLine('<error>' . (sprintf('No parts in Preset "%s" defined', $preset)) . '</error>');
            $this->quit(1);
        }

        $presetParts = array_keys($presetSettings['parts']);
        $parts = $parts ? Arrays::trimExplode(',', $parts) : $presetParts;

        $partDiffs = array_diff($parts, $presetParts);
        if (count($partDiffs) > 0) {
            $this->output->outputLine('<error>' . (sprintf('The preset "%s" does not have the parts: %s', $preset, implode(', ', $partDiffs))) . '</error>');
            return;
        }

        array_walk($presetSettings['parts'], function ($partSetting, $partName) use ($preset, $parts, $method) {
            $this->output->outputLine('<notice>' . (sprintf('Process Preset "%s" / Part "%s"', $preset, $partName)) . '</notice>');
            if ($parts !== [] && !in_array($partName, $parts)) {
                $this->output->outputLine('<warning>Skipped</warning>');
                return;
            }

            $method($preset, $partName);
            $this->output->outputLine('<success>Done</success>');
        });
    }

    /**
     * Cleanup the presets parts
     *
     * @param string $partName
     * @param string $preset
     * @return void
     */
    protected function cleanupPresetParts(string $partName, string $preset): void
    {
        $this->output->outputLine(sprintf('Cleanup part "%s" of preset "%s"', $partName, $preset));
        $importDatas = $this->importDataRepository->findByPresetNameAndPartName($preset, $partName);
        foreach ($importDatas as $importData) {
            $this->importDataRepository->remove($importData);
        }
    }
}
