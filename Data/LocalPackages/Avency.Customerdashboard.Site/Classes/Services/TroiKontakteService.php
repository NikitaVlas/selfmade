<?php
namespace Avency\Customerdashboard\Site\Services;

use Neos\Flow\Annotations as Flow;

/**
 * CustomerService
 */
class TroiKontakteService
{
    /**
     * @Flow\Inject
     * @var DatabaseService
     */
    protected $dbService;

    public function getTroiContacts(int $offset = null, int $limit = null): array
    {
        $conn = $this->dbService->getConnection();
        $query = '
            SELECT
              troi_kontakte.ID_Kontakt as identifier,
              troi_kontakte.Vorname as vorname,
              troi_kontakte.Nachname as nachname,
              troi_kontakte.deleted as deleted,
              troi_kontakte.E_Mail as email,
              troi_kontakte.Telefon as telefon
            FROM
              troi_kontakte
        ';

        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        if ($offset) {
            $query .= ' OFFSET ' . $offset;
        }

        $query = $conn->prepare($query);
        $query->execute();
        $contact = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $contact;
    }

    public function getTroiContactPersonById(string $contactPersonId): array
    {
        $conn = $this->dbService->getConnection();
        $query = '
            SELECT
              troi_kontakte.ID_Kontakt as identifier,
              troi_kontakte.Vorname as vorname,
              troi_kontakte.Nachname as nachname,
              troi_kontakte.deleted as deleted,
              troi_kontakte.E_Mail as email,
              troi_kontakte.Telefon as telefon
            FROM
              troi_kontakte
            WHERE ID_Kontakt = "' . $contactPersonId.'"
        ';
        $query = $conn->prepare($query);
        $query->execute();
        $contact = $query->fetchAll(\PDO::FETCH_ASSOC);

        return count($contact) > 0 ? $contact[0] : [];
    }
}
