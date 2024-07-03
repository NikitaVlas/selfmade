<?php

namespace Avency\Neos\Importer\Domain\Model;

/**
 * Preset Definition
 */
class PresetPartDefinition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $part;

    /**
     * @var string
     */
    protected $dataProviderClassName;

    /**
     * @var array
     */
    protected $dataProviderOptions = [];

    /**
     * @var string
     */
    protected $importerClassName;

    /**
     * @var array
     */
    protected $importerOptions = [];

    /**
     * @var bool
     */
    protected $removeImportDataDirectly = false;

    /**
     * @var bool
     */
    protected $skipPrepareIfDataExists = false;

    /**
     * @var bool
     */
    protected $skipPrepareBatchIfDataExists = false;

    /**
     * @param array $settings
     * @throws \Exception
     */
    public function __construct(array $settings)
    {
        if (!isset($settings['__currentPresetName'])) {
            throw new \Exception('Missing or invalid "__currentPresetName" in preset settings', 1464090202);
        }
        $this->name = $settings['__currentPresetName'];

        if (!isset($settings['__currentPresetPart'])) {
            throw new \Exception('Missing or invalid "__currentPresetPart" in preset settings', 1464090202);
        }
        $this->part = $settings['__currentPresetPart'];

        if (!isset($settings['dataProviderClassName']) || !is_string($settings['dataProviderClassName'])) {
            throw new \Exception('Missing or invalid "dataProviderClassName" in preset settings', 1464090203);
        }
        $this->dataProviderClassName = (string)$settings['dataProviderClassName'];

        if (isset($settings['dataProviderOptions']) && is_array($settings['dataProviderOptions'])) {
            $this->dataProviderOptions = $settings['dataProviderOptions'];
        }

        if (!isset($settings['importerClassName']) || !is_string($settings['importerClassName'])) {
            throw new \Exception('Missing or invalid "importerClassName" in preset settings', 1464090203);
        }
        $this->importerClassName = (string)$settings['importerClassName'];

        if (isset($settings['removeImportDataDirectly'])) {
            if (!is_bool($settings['removeImportDataDirectly'])) {
                throw new \Exception('"removeImportDataDirectly" must be boolean!', 1683030287);
            }
            $this->removeImportDataDirectly = $settings['removeImportDataDirectly'];
        }

        if (isset($settings['skipPrepareIfDataExists'])) {
            if (!is_bool($settings['skipPrepareIfDataExists'])) {
                throw new \Exception('"skipPrepareIfDataExists" must be boolean!', 1683620131);
            }
            $this->skipPrepareIfDataExists = $settings['skipPrepareIfDataExists'];
        }

        if (isset($settings['skipPrepareBatchIfDataExists'])) {
            if (!is_bool($settings['skipPrepareBatchIfDataExists'])) {
                throw new \Exception('"skipPrepareBatchIfDataExists" must be boolean!', 1683633752);
            }
            $this->skipPrepareBatchIfDataExists = $settings['skipPrepareBatchIfDataExists'];
        }

        if (isset($settings['importerOptions']) && is_array($settings['importerOptions'])) {
            $this->importerOptions = $settings['importerOptions'];
        }
    }

    /**
     * Getter for Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * Getter for DataProviderClassName
     *
     * @return string
     */
    public function getDataProviderClassName(): string
    {
        return $this->dataProviderClassName;
    }

    /**
     * Getter for DataProviderOptions
     *
     * @return array
     */
    public function getDataProviderOptions(): array
    {
        return $this->dataProviderOptions;
    }

    /**
     * Getter for ImporterClassName
     *
     * @return string
     */
    public function getImporterClassName(): string
    {
        return $this->importerClassName;
    }

    /**
     * Getter for ImporterOptions
     *
     * @return array
     */
    public function getImporterOptions(): array
    {
        return $this->importerOptions;
    }

    /**
     * Getter for RemoveImportDataDirectly
     *
     * @return bool
     */
    public function isRemoveImportDataDirectly(): bool
    {
        return $this->removeImportDataDirectly;
    }

    /**
     * Getter for SkipPrepareIfDataExists
     *
     * @return bool
     */
    public function isSkipPrepareIfDataExists(): bool
    {
        return $this->skipPrepareIfDataExists;
    }

    /**
     * Getter for skipPrepareBatchIfDataExists
     * @return bool
     */
    public function isSkipPrepareBatchIfDataExists(): bool
    {
        return $this->skipPrepareBatchIfDataExists;
    }
}
