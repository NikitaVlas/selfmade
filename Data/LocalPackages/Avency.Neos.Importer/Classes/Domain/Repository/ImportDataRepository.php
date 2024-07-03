<?php

namespace Avency\Neos\Importer\Domain\Repository;

use Avency\Neos\Importer\Domain\Model\ImportData;
use Avency\Neos\Importer\Domain\Model\ImportVersion;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class ImportDataRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sort' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Get the import data of a preset and part
     *
     * @param string $preset
     * @param string $partName
     * @return QueryResultInterface<ImportData>
     */
    public function findByPresetNameAndPartName(string $preset, string $partName): QueryResultInterface
    {
        return $this->getByPresetNameAndPartName($preset, $partName)->execute();
    }

    /**
     * Count the import data of a preset and part
     *
     * @param string $preset
     * @param string $partName
     * @return int
     */
    public function countByPresetNameAndPartName(string $preset, string $partName): int
    {
        return $this->getByPresetNameAndPartName($preset, $partName)->count();
    }

    /**
     * Get the import data of a preset and part and a specific import version
     *
     * @param string $preset
     * @param string $partName
     * @param ImportVersion $importVersion
     * @return QueryResultInterface<ImportData>
     */
    public function findByPresetNameAndPartNameAndImportVersion(string $preset, string $partName, ImportVersion $importVersion): QueryResultInterface
    {
        return $this->getByPresetNameAndPartNameAndImportVersion($preset, $partName, $importVersion)->execute();
    }

    /**
     * Count the import data of a preset and part and a specific import version
     *
     * @param string $preset
     * @param string $partName
     * @param ImportVersion $importVersion
     * @return int
     */
    public function countByPresetNameAndPartNameAndImportVersion(string $preset, string $partName, ImportVersion $importVersion): int
    {
        return $this->getByPresetNameAndPartNameAndImportVersion($preset, $partName, $importVersion)->count();
    }

    /**
     * Get the import data of a preset and part which are not of the same import version
     *
     * @param string $preset
     * @param string $partName
     * @param ImportVersion $importVersion
     * @return int
     */
    public function findByPresetNameAndPartNameAndNotImportVersion(string $preset, string $partName, ImportVersion $importVersion): int
    {
        return $this->getByPresetNameAndPartNameAndNotImportVersion($preset, $partName, $importVersion)->count();
    }

    /**
     * Count the import data of a preset and part which are not of the same import version
     *
     * @param string $preset
     * @param string $partName
     * @param ImportVersion $importVersion
     * @return int
     */
    public function countByPresetNameAndPartNameAndNotImportVersion(string $preset, string $partName, ImportVersion $importVersion): int
    {
        return $this->getByPresetNameAndPartNameAndNotImportVersion($preset, $partName, $importVersion)->count();
    }

    /**
     * Get the import data of a preset and part
     *
     * @param string $preset
     * @param string $partName
     * @return QueryInterface
     */
    protected function getByPresetNameAndPartName(string $preset, string $partName): QueryInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('presetName', $preset),
                $query->equals('part', $partName)
            )
        );
        return $query;
    }

    /**
     * Get the import data of a preset and part and a specific import version
     *
     * @param string $preset
     * @param string $partName
     * @param ImportVersion $importVersion
     * @return QueryInterface
     */
    protected function getByPresetNameAndPartNameAndImportVersion(string $preset, string $partName, ImportVersion $importVersion): QueryInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('presetName', $preset),
                $query->equals('part', $partName),
                $query->equals('importVersion', $importVersion)
            )
        );
        return $query;
    }


    /**
     * Get the import data of a preset and part which are not of the same import version
     *
     * @param string $preset
     * @param string $partName
     * @param ImportVersion $importVersion
     * @return QueryInterface
     */
    public function getByPresetNameAndPartNameAndNotImportVersion(string $preset, string $partName, ImportVersion $importVersion): QueryInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->logicalAnd(
                    $query->equals('presetName', $preset),
                    $query->equals('part', $partName)
                ),
                $query->logicalNot(
                    $query->equals('importVersion', $importVersion)
                )
            )
        );

        return $query;
    }
}
