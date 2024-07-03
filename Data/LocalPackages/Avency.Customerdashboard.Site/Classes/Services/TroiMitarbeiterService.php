<?php
namespace Avency\Customerdashboard\Site\Services;

use Neos\Flow\Annotations as Flow;

/**
 * CustomerService
 */
class TroiMitarbeiterService
{
    /**
     * @Flow\Inject
     * @var DatabaseService
     */
    protected $dbService;

    public function getTroiMitarbeiter(int $offset = null, int $limit = null): array
    {
        $conn = $this->dbService->getConnection();
        $query = '
            SELECT
              troi_mitarbeiter.ID_Mitarbeiter as identifier,
              troi_mitarbeiter.Vorname as vorname,
              troi_mitarbeiter.Nachname as nachname,
              troi_mitarbeiter.employee_is_deleted as deleted,
              troi_mitarbeiter.Kurzzeichen as kurzzeichen
            FROM
              troi_mitarbeiter
        ';

        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset) {
            $query .= ' OFFSET ' . $offset;
        }

        $query = $conn->prepare($query);
        $query->execute();
        $person = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $person;
    }

    public function getTroiPersonById(string $troiIdPerson): array
    {
        $conn = $this->dbService->getConnection();
        $query = '
            SELECT
              troi_mitarbeiter.ID_Mitarbeiter as identifier,
              troi_mitarbeiter.Vorname as vorname,
              troi_mitarbeiter.Nachname as nachname,
              troi_mitarbeiter.employee_is_deleted as deleted,
              troi_mitarbeiter.Kurzzeichen as kurzzeichen
            FROM
              troi_mitarbeiter
            WHERE ID_Mitarbeiter = "' . $troiIdPerson.'"
            LIMIT 1
        ';
        $query = $conn->prepare($query);
        $query->execute();
        $customer = $query->fetchAll(\PDO::FETCH_ASSOC);

        return count($customer) > 0 ? $customer[0] : [];
    }
}
