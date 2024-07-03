<?php

namespace Avency\Neos\Importer\Domain\Repository;

use Avency\Neos\Importer\Domain\Model\RecordMapping;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class RecordMappingRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'externalIdentifier' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Find by preset and part
     *
     * @param string $preset
     * @param string $part
     * @param int|null $offset
     * @param int|null $limit
     * @return QueryResultInterface
     */
    public function findByPresetAndPart(string $preset, string $part, int $offset = null, int $limit = null)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('preset', $preset),
                $query->equals('part', $part)
            )
        );
        if ($offset != null) {
            $query->setOffset($offset);
        }
        if ($limit != null) {
            $query->setLimit($limit);
        }
        return $query->execute();
    }

    /**
     * Find one by preset, part and externalIdentifier
     *
     * @param string $preset
     * @param string $part
     * @param string $externalIdentifier
     * @return RecordMapping
     */
    public function findOneByPresetPartAndExternalIdentifier(string $preset, string $part, string $externalIdentifier)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('preset', $preset),
                $query->equals('part', $part),
                $query->equals('externalIdentifier', $externalIdentifier)
            )
        );
        return $query->execute()->getFirst();
    }

    /**
     * Find one by preset, part and alternativeIdentifier
     *
     * @param string $preset
     * @param string $part
     * @param string $alternativeIdentifier
     * @return RecordMapping
     */
    public function findOneByPresetPartAndAlternativeIdentifier(string $preset, string $part, string $alternativeIdentifier)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('preset', $preset),
                $query->equals('part', $part),
                $query->equals('additionalIdentifier', $alternativeIdentifier)
            )
        );
        return $query->execute()->getFirst();
    }
}
