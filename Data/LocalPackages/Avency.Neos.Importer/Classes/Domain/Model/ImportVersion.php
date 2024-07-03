<?php

namespace Avency\Neos\Importer\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Entity
 */
class ImportVersion
{
    public const STATUS_NEW = 'new';

    /**
     * Preparing data
     */
    public const STATUS_PREPARING = 'preparing';

    /**
     * Preparing is finished
     */
    public const STATUS_READY_TO_IMPORT = 'ready_to_import';

    /**
     * Import is in progress: prepare batch
     */
    public const STATUS_IN_PROGRESS_PREPARE_BATCH = 'import_prepare_batch';

    /**
     * Import is in progress: importing data
     */
    public const STATUS_IN_PROGRESS_IMPORT = 'import_in_progress';

    /**
     * Import is finished
     */
    public const STATUS_FINISHED = 'imported';

    /**
     * Import caused an error
     */
    public const STATUS_ERROR = 'error';

    /**
     * @var string
     */
    protected $importVersion;

    /**
     * @var string
     */
    protected $preset;

    /**
     * @var string
     */
    protected $part;

    /**
     * @var string
     */
    protected $status = self::STATUS_NEW;

    /**
     * @ORM\OneToMany(mappedBy="importVersion")
     * @var Collection<ImportData>
     */
    protected $importDatas;

    public function __construct()
    {
        $date = new \DateTime('now');
        $this->importVersion = (string) $date->getTimestamp();

        $this->importDatas = new ArrayCollection();
    }

    /**
     * Getter for import version
     *
     * @return string
     */
    public function getImportVersion(): string
    {
        return $this->importVersion;
    }

    /**
     * Setter for import version
     *
     * @param string $importVersion
     * @return self
     */
    public function setImportVersion(string $importVersion): ImportVersion
    {
        $this->importVersion = $importVersion;

        return $this;
    }

    /**
     * Getter for import datas
     *
     * @return ArrayCollection|Collection
     */
    public function getImportDatas()
    {
        return $this->importDatas;
    }

    /**
     * Setter for status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Setter for status
     *
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): ImportVersion
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Returns true if the import is finished
     *
     * @return bool
     */
    public function isImported(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    /**
     * Returns true if no new prepare should be runned
     *
     * @return bool
     */
    public function skipPrepare(): bool
    {
        return $this->getStatus() === self::STATUS_PREPARING
            || $this->getStatus() === self::STATUS_IN_PROGRESS_PREPARE_BATCH
            || $this->getStatus() === self::STATUS_IN_PROGRESS_IMPORT
            || $this->getStatus() === self::STATUS_READY_TO_IMPORT;
    }

    /**
     * Setter for presetName
     *
     * @return string
     */
    public function getPreset(): string
    {
        return $this->preset;
    }

    /**
     * Getter for presetName
     *
     * @param string $preset
     */
    public function setPreset(string $preset): void
    {
        $this->preset = $preset;
    }

    /**
     * Setter for part
     *
     * @return string
     */
    public function getPart(): string
    {
        return $this->part;
    }

    /**
     * Getter for part
     *
     * @param string $part
     */
    public function setPart(string $part): void
    {
        $this->part = $part;
    }
}
