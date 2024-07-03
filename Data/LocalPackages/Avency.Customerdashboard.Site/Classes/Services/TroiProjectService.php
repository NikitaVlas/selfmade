<?php
namespace Avency\Customerdashboard\Site\Services;

use Neos\Flow\Annotations as Flow;

/**
 * CustomerService
 */
class TroiProjectService
{
    /**
     * @Flow\Inject
     * @var DatabaseService
     */
    protected $dbService;

    public function getTroiProjects(int $offset = null, int $limit = null): array
    {
        $conn = $this->dbService->getConnection();
        $query = '
            SELECT
              troi_project.project_id as identifier,
              troi_project.project_name as projectname,
              troi_project.project_number as projectnumber,
              troi_project.project_is_deleted as deleted,
              troi_project.project_customer_id as customerId,
              troi_project.project_leader_id as projectLeader
            FROM
              troi_project
        ';

        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset) {
            $query .= ' OFFSET ' . $offset;
        }

        $query = $conn->prepare($query);
        $query->execute();
        $project = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $project;
    }
}
