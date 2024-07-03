<?php

namespace Avency\Neos\Importer\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Entity
 * @ORM\Table(indexes={
 *     @ORM\Index(name="preset_part", columns={"preset", "part"}),
 *     @ORM\Index(name="externalidentifier", columns={"externalidentifier"})
 * })
 */
class RecordMapping
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
     * @var string
     */
    protected $externalIdentifier;

    /**
     * @var string
     */
    protected $nodeIdentifier;

    /**
     * @var string
     */
    protected $dataHash;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $additionalIdentifier;

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
     * Setter for Preset
     *
     * @param string $preset
     * @return self
     */
    public function setPreset(string $preset)
    {
        $this->preset = $preset;
        return $this;
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

    /**
     * Setter for Part
     *
     * @param string $part
     * @return self
     */
    public function setPart(string $part)
    {
        $this->part = $part;
        return $this;
    }

    /**
     * Getter for ExternalIdentifier
     *
     * @return string
     */
    public function getExternalIdentifier()
    {
        return $this->externalIdentifier;
    }

    /**
     * Setter for ExternalIdentifier
     *
     * @param string $externalIdentifier
     * @return self
     */
    public function setExternalIdentifier(string $externalIdentifier)
    {
        $this->externalIdentifier = $externalIdentifier;
        return $this;
    }

    /**
     * Getter for NodeIdentifier
     *
     * @return string
     */
    public function getNodeIdentifier()
    {
        return $this->nodeIdentifier;
    }

    /**
     * Setter for NodeIdentifier
     *
     * @param string $nodeIdentifier
     * @return self
     */
    public function setNodeIdentifier(string $nodeIdentifier)
    {
        $this->nodeIdentifier = $nodeIdentifier;
        return $this;
    }

    /**
     * Getter for DataHash
     *
     * @return string
     */
    public function getDataHash()
    {
        return $this->dataHash;
    }

    /**
     * Setter for DataHash
     *
     * @param string $dataHash
     * @return self
     */
    public function setDataHash(string $dataHash)
    {
        $this->dataHash = $dataHash;
        return $this;
    }

    /**
     * Getter for AdditionalIdentifier
     *
     * @return string
     */
    public function getAdditionalIdentifier()
    {
        return $this->additionalIdentifier;
    }

    /**
     * Setter for AdditionalIdentifier
     *
     * @param string $additionalIdentifier
     * @return self
     */
    public function setAdditionalIdentifier(string $additionalIdentifier)
    {
        $this->additionalIdentifier = $additionalIdentifier;
        return $this;
    }
}
