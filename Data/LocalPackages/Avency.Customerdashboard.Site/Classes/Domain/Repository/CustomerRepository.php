<?php
namespace Avency\Customerdashboard\Site\Domain\Repository;

use Avency\Customerdashboard\Site\Domain\Model\Customer;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class CustomerRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Find customers by title
     *
     * @param string $title
     * @return QueryInterface
     */
    public function findAllByTitle(string $title): QueryInterface
    {
        $query = $this->createQuery();

        $query->matching(
            $query->like('title', '%' . $title . '%')
        );

        return $query;
    }
}
