<?php

namespace Avency\Neos\Importer\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Entity
 * @ORM\Table(
 *    indexes={
 *      @ORM\Index(name="sortingindex",columns={"sort"}),
 *    }
 * )
 */
class ImportData
{
    /**
     * @var string
     */
    protected $presetName;

    /**
     * @var string
     */
    protected $part;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $sort;

    /**
     * @ORM\ManyToOne(inversedBy="importDatas", cascade={"persist"})
     * @ORM\Column(nullable=true)
     * @var ImportVersion|null
     */
    protected $importVersion = null;

    /**
     * Getter for PresetName
     *
     * @return string
     */
    public function getPresetName(): string
    {
        return $this->presetName;
    }

    /**
     * Setter for PresetName
     *
     * @param string $presetName
     * @return self
     */
    public function setPresetName(string $presetName): ImportData
    {
        $this->presetName = $presetName;
        return $this;
    }

    /**
     * Getter for Part
     *
     * @return string
     */
    public function getPart(): string
    {
        return $this->part;
    }

    /**
     * Setter for Part
     *
     * @param string $part
     * @return self
     */
    public function setPart(string $part): ImportData
    {
        $this->part = $part;
        return $this;
    }

    /**
     * Getter for Data
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Setter for Data
     *
     * @param string $data
     * @return self
     */
    public function setData(string $data): ImportData
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return self
     */
    public function setSort(string $sort): ImportData
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Getter for import version
     *
     * @return ImportVersion|null
     */
    public function getImportVersion(): ?ImportVersion
    {
        return $this->importVersion;
    }

    /**
     * Setter for import version
     *
     * @param ImportVersion $importVersion
     */
    public function setImportVersion(ImportVersion $importVersion): void
    {
        $this->importVersion = $importVersion;
    }
}
