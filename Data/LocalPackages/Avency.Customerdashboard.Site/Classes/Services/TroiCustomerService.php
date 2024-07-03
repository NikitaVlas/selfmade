<?php
namespace Avency\Customerdashboard\Site\Services;

use Neos\Flow\Annotations as Flow;

/**
 * CustomerService
 */
class TroiCustomerService
{
    /**
     * @Flow\Inject
     * @var DatabaseService
     */
    protected $dbService;

    public function getTroiCustomers(int $offset = null, int $limit = null): array
    {
        $conn = $this->dbService->getConnection();
        $query = '
            SELECT
              troi_kunden.ID_Kunde as identifier,
              troi_kunden.Kundenname as kundenname,
              troi_kunden.Projektkuerzel as projektkuerzel,
              troi_kunden.customer_number as customernumber,
              troi_kunden.deleted as deleted,
              troi_kunden.ID_Kontakt as contactPersonId
            FROM
              troi_kunden
        ';

        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset) {
            $query .= ' OFFSET ' . $offset;
        }

        $query = $conn->prepare($query);
        $query->execute();
        $customer = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $customer;
    }
    public function getTroiCustomerById(string $customerId): array
    {
        $conn = $this->dbService->getConnection();
        $query = '
            SELECT
              troi_kunden.ID_Kunde as identifier,
              troi_kunden.Kundenname as kundenname,
              troi_kunden.Projektkuerzel as projektkuerzel,
              troi_kunden.customer_number as customernumber,
              troi_kunden.deleted as deleted
            FROM
              troi_kunden
            WHERE ID_Kunde = "' . $customerId.'"
            LIMIT 1
        ';
        $query = $conn->prepare($query);
        $query->execute();
        $customer = $query->fetchAll(\PDO::FETCH_ASSOC);

        return count($customer) > 0 ? $customer[0] : [];
    }
}
