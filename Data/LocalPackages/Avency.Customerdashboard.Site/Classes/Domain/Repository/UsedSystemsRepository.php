<?php

namespace Avency\Customerdashboard\Site\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class UsedSystemsRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'customer' => QueryInterface::ORDER_ASCENDING,
    ];
}
