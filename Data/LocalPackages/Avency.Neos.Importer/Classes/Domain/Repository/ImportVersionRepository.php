<?php

namespace Avency\Neos\Importer\Domain\Repository;

use Avency\Neos\Importer\Domain\Model\ImportVersion;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class ImportVersionRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'importVersion' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Get a import version of a preset and part that is not fully imported yet
     *
     * @param string $preset
     * @param string $part
     * @return ImportVersion|null
     */
    public function findOneByPresetAndPartAndIsNotFinished(string $preset, string $part): ?ImportVersion
    {
        $query = $this->createQuery();

        $query->matching(
            $query->logicalAnd(
                $query->logicalAnd(
                    $query->equals('preset', $preset),
                    $query->equals('part', $part)
                ),
                $query->logicalOr(
                    $query->equals('status', ImportVersion::STATUS_PREPARING),
                    $query->equals('status', ImportVersion::STATUS_IN_PROGRESS_PREPARE_BATCH),
                    $query->equals('status', ImportVersion::STATUS_IN_PROGRESS_IMPORT),
                    $query->equals('status', ImportVersion::STATUS_READY_TO_IMPORT)
                )
            )
        );

        return $query->execute()->getFirst();
    }

    /**
     * Get a import version of a preset and part that is ready to import
     *
     * @param string $preset
     * @param string $part
     * @return ImportVersion|null
     */
    public function findOneByPresetAndPartAndIsReadyToImport(string $preset, string $part): ?ImportVersion
    {
        $query = $this->createQuery();

        $query->matching(
            $query->logicalAnd(
                $query->logicalAnd(
                    $query->equals('preset', $preset),
                    $query->equals('part', $part),
                    $query->equals('status', ImportVersion::STATUS_READY_TO_IMPORT)
                )
            )
        );

        return $query->execute()->getFirst();
    }

    /**
     * Get a import version of a preset and part that is ready to import or already importing
     *
     * @param string $preset
     * @param string $part
     * @return ImportVersion|null
     */
    public function findOneByPresetAndPartAndIsReadyToImportOrInProcess(string $preset, string $part): ?ImportVersion
    {
        $query = $this->createQuery();

        $query->matching(
            $query->logicalAnd(
                $query->logicalAnd(
                    $query->equals('preset', $preset),
                    $query->equals('part', $part)
                ),
                $query->logicalOr(
                    $query->equals('status', ImportVersion::STATUS_READY_TO_IMPORT),
                    $query->equals('status', ImportVersion::STATUS_IN_PROGRESS_PREPARE_BATCH),
                    $query->equals('status', ImportVersion::STATUS_IN_PROGRESS_IMPORT)
                )
            )
        );

        return $query->execute()->getFirst();
    }
}
