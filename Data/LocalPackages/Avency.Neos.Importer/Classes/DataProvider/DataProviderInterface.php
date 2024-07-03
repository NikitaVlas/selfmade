<?php

namespace Avency\Neos\Importer\DataProvider;

/**
 * DataProvider interface
 */
interface DataProviderInterface
{
    /**
     * @param array $options
     * @param string $preset
     * @param string $part
     * @return self
     */
    public static function create(array $options, string $preset, string $part);

    /**
     * Fetch data for import
     *
     * @param int|null $offset
     * @param int|null $limit
     * @return array
     */
    public function fetch(int $offset = null, int $limit = null);

    /**
     * Prepare data for import
     *
     * @return void
     */
    public function prepare();

    /**
     * Get data count
     *
     * @return int
     */
    public function getCount();

    /**
     * Getter for Preset
     *
     * @return string
     */
    public function getPreset();

    /**
     * Getter for Part
     *
     * @return string
     */
    public function getPart();
}
