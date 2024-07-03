<?php

namespace Avency\Neos\Importer\Importer;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\ConsoleOutput;

/**
 * Abstract Importer
 * @Flow\Scope("singleton")
 */
class AbstractImporter
{
    /**
     * @var string
     */
    protected $preset;

    /**
     * @var string
     */
    protected $part;

    /**
     * @var ConsoleOutput
     */
    protected $output;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param string $preset
     * @param string $part
     * @param ConsoleOutput $output
     * @param array $importerOptions
     * @return static
     */
    public static function create(string $preset, string $part, ConsoleOutput $output, array $importerOptions = [])
    {
        $importer = new static();
        $importer->preset = $preset;
        $importer->part = $part;
        $importer->output = $output;
        $importer->options = $importerOptions;
        return $importer;
    }

    /**
     * Prepare import
     *
     * @return void
     */
    public function prepare()
    {
    }

    /**
     * Prepare Batch import
     *
     * @return void
     */
    public function prepareBatch()
    {
    }

    /**
     * Check Data
     *
     * @param array $data
     * @return bool
     */
    public function checkData(array $data)
    {
        return true;
    }

    /**
     * Getter for preset
     *
     * @return string
     */
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * Getter for part of preset
     *
     * @return string
     */
    public function getPart()
    {
        return $this->part;
    }
}
