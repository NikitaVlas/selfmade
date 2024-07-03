<?php

namespace Avency\Neos\Importer\Domain\Service;

use Avency\Neos\Importer\Domain\Model\RecordMapping;
use Avency\Neos\Importer\Domain\Repository\RecordMappingRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;

/**
 * RecordMapping Service
 *
 * @Flow\Scope("singleton")
 */
class RecordMappingService
{
    /**
     * @Flow\Inject
     * @var RecordMappingRepository
     */
    protected $recordMappingRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Add or update a record mapping
     *
     * @param string $preset
     * @param string $part
     * @param string $externalIdentifier
     * @param string $nodeIdentifier
     * @param string $dataHash
     * @param string $addtionalIdentifier
     * @return void
     */
    public function set(string $preset, string $part, string $externalIdentifier, string $nodeIdentifier, string $dataHash, string $addtionalIdentifier = null)
    {
        $recordMapping = $this->get($preset, $part, $externalIdentifier);
        if ($recordMapping == null) {
            $recordMapping = new RecordMapping();
        }

        if ($recordMapping == null || ($recordMapping instanceof RecordMapping && $recordMapping->getDataHash() != $dataHash)) {
            $recordMapping->setPreset($preset);
            $recordMapping->setPart($part);
            $recordMapping->setExternalIdentifier($externalIdentifier);
            $recordMapping->setNodeIdentifier($nodeIdentifier);
            $recordMapping->setDataHash($dataHash);
            if ($addtionalIdentifier !== null) {
                $recordMapping->setAdditionalIdentifier($addtionalIdentifier);
            }
            if ($this->persistenceManager->isNewObject($recordMapping)) {
                $this->recordMappingRepository->add($recordMapping);
            } else {
                $this->recordMappingRepository->update($recordMapping);
            }
        }
    }

    /**
     * Get a recordMapping if available
     *
     * @param string $preset
     * @param string $part
     * @param string $externalIdentifier
     * @return RecordMapping
     */
    public function get(string $preset, string $part, string $externalIdentifier)
    {
        return $this->recordMappingRepository->findOneByPresetPartAndExternalIdentifier($preset, $part, $externalIdentifier);
    }

    /**
     * Get a recordMapping if available
     *
     * @param string $preset
     * @param string $part
     * @param string $alternativeIdentifier
     * @return RecordMapping
     */
    public function getByAlternativeIdentifier(string $preset, string $part, string $alternativeIdentifier)
    {
        return $this->recordMappingRepository->findOneByPresetPartAndAlternativeIdentifier($preset, $part, $alternativeIdentifier);
    }
}
