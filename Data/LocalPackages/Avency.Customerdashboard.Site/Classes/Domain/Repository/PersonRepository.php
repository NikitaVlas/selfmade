<?php
namespace Avency\Customerdashboard\Site\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class PersonRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'firstName' => QueryInterface::ORDER_ASCENDING,
        'lastName' => QueryInterface::ORDER_ASCENDING,
    ];
}
