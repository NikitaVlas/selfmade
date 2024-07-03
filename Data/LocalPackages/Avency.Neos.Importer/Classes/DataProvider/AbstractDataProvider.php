<?php

namespace Avency\Neos\Importer\DataProvider;

use Neos\Flow\Annotations as Flow;

/**
 * Abstract Data Provider
 * @Flow\Scope("singleton")
 */
abstract class AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $preset;

    /**
     * @var  string
     */
    protected $part;

    /**
     * @var int
     */
    protected $count;

    /**
     * @param array $options
     * @return self
     */
    public static function create(array $options, string $preset, string $part)
    {
        $dataProvider = new static();
        $dataProvider->options = $options;
        $dataProvider->preset = $preset;
        $dataProvider->part = $part;
        return $dataProvider;
    }

    /**
     * Prepare data for import
     *
     * @return void
     */
    public function prepare()
    {
    }

    /**
     * Get data count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Getter for Preset
     *
     * @return string
     */
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * Getter for Part
     *
     * @return string
     */
    public function getPart()
    {
        return $this->part;
    }
}
