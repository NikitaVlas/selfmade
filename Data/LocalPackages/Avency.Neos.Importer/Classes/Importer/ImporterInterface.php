<?php

namespace Avency\Neos\Importer\Importer;

use Neos\Flow\Cli\ConsoleOutput;

interface ImporterInterface
{
    /**
     * @param string $preset
     * @param string $part
     * @param ConsoleOutput $output
     * @param array $importerOptions
     * @return self
     */
    public static function create(string $preset, string $part, ConsoleOutput $output, array $importerOptions = []);

    /**
     * @return void
     */
    public function prepareBatch();

    /**
     * @return void
     */
    public function prepare();

    /**
     * @param array $data
     */
    public function import(array $data);

    /**
     * @param array $data
     * @return bool
     */
    public function checkData(array $data);

    /**
     * @return string
     */
    public function getPreset();

    /**
     * @return string
     */
    public function getPart();
}
