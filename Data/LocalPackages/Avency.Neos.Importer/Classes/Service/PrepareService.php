<?php

namespace Avency\Neos\Importer\Service;

use Avency\Neos\Importer\DataProvider\DataProviderInterface;
use Avency\Neos\Importer\Domain\Model\DataInterface;
use Avency\Neos\Importer\Domain\Model\ImportVersion;
use Avency\Neos\Importer\Domain\Model\PresetPartDefinition;
use Avency\Neos\Importer\Domain\Repository\ImportVersionRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Utility\Algorithms;
use Psr\Log\LoggerInterface;

/**
 * Prepare data service
 *
 * @Flow\Scope("singleton")
 */
class PrepareService
{
    /**
     * @Flow\Inject(name="Avency.Neos.Importer:NeosImporterLogger")
     * @var LoggerInterface
     */
    protected $neosImporterLogger;

    /**
     * @Flow\Inject
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @Flow\Inject
     * @var EmailService
     */
    protected $emailService;

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
     * Prepare data for import
     *
     * @param PresetPartDefinition $presetPartDefinition
     * @param int|null $offset
     * @param int|null $limit
     * @param ImportVersion|null $importVersion
     * @param bool $skipPrepareSkipCheck
     * @return boolean
     * @throws \Exception
     */
    public function prepareData(PresetPartDefinition $presetPartDefinition, int $offset = null, int $limit = null, ImportVersion $importVersion = null, bool $skipPrepareSkipCheck = false)
    {
        if (!$importVersion instanceof ImportVersion) {
            $importVersion = new ImportVersion();
            $importVersion->setStatus(ImportVersion::STATUS_NEW);
            $importVersion->setPreset($presetPartDefinition->getName());
            $importVersion->setPart($presetPartDefinition->getPart());

            $this->importVersionRepository->add($importVersion);
            $this->persistenceManager->persistAll();
        }

        if ($presetPartDefinition->isSkipPrepareIfDataExists() && $importVersion->skipPrepare() && !$skipPrepareSkipCheck) {
            $message = sprintf('The Preset "%s" / Part "%s" already has an import in progress which is not fully imported yet. Skipping this preset.', $presetPartDefinition->getName(), $presetPartDefinition->getPart());
            $this->neosImporterLogger->warning($message);
            return false;
        }

        try {
            /** @var DataProviderInterface $dataProvider */
            $dataProviderClassName = $presetPartDefinition->getDataProviderClassName();
            $dataProvider = $dataProviderClassName::create(
                $presetPartDefinition->getDataProviderOptions(),
                $presetPartDefinition->getName(),
                $presetPartDefinition->getPart()
            );

            $importVersion->setStatus(ImportVersion::STATUS_PREPARING);
            $this->importVersionRepository->update($importVersion);
            $this->persistenceManager->persistAll();

            if ($offset == 0 || $offset === null) {
                $dataProvider->prepare();
            }

            $count = $dataProvider->getCount();
            if (!empty($count) && $count > $offset) {
                $batchData = $dataProvider->fetch($offset, $limit);
                foreach ($batchData as $key => $data) {
                    $this->saveData($presetPartDefinition, $data, $key, $importVersion);
                }
            }

            if ($count <= ($limit + $offset)) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            $subject = 'Error in DataProvider on ' . getenv('FLOW_CONTEXT');
            $content = 'Error in DataProvider: ' . $presetPartDefinition->getDataProviderClassName() . '<br><br><hr><pre>' . $e->getTraceAsString() . '</pre><br><br><hr><pre>' . $e->getMessage() . '</pre>';

            $this->emailService->sendErrorMail($subject, $content);
            $importVersion->setStatus(ImportVersion::STATUS_ERROR);
            $this->importVersionRepository->update($importVersion);
            $this->persistenceManager->persistAll();

            throw $e;
        }
    }

    /**
     * Save prepared data
     *
     * @param PresetPartDefinition $presetPartDefinition
     * @param array|DataInterface $data
     * @param string $key
     * @param ImportVersion $importVersion
     * @return void
     */
    protected function saveData(PresetPartDefinition $presetPartDefinition, $data, string $key, ImportVersion $importVersion)
    {
        /** @var Connection $connection */
        $connection = $this->entityManager->getConnection();
        $statement = $connection->prepare(
            'INSERT INTO
                avency_neos_importer_domain_model_importdata
            (
                `persistence_object_identifier`,
                `presetname`,
                `part`,
                `data`,
                `sort`,
                `importversion`
            ) VALUES (
                :persistence_object_identifier,
                :presetname,
                :part,
                :data,
                :sort,
                :importversion
            )'
        );
        $params = [
            'persistence_object_identifier' => Algorithms::generateUUID(),
            'presetname' => $presetPartDefinition->getName(),
            'part' => $presetPartDefinition->getPart(),
            'sort' => $key,
            'importversion' => $this->persistenceManager->getIdentifierByObject($importVersion),
        ];

        if ($data instanceof DataInterface) {
            $params['data'] = json_encode($data->toArray());
        } else {
            $params['data'] = json_encode($data);
        }

        $statement->execute($params);
    }
}
