<?php
namespace Avency\Customerdashboard\Site\DataProvider;

use Avency\Customerdashboard\Site\Services\TroiKontakteService;
use Avency\Neos\Importer\DataProvider\AbstractDataProvider;
use Neos\Flow\Annotations as Flow;

class ContactPersonDataProvider extends AbstractDataProvider
{
    /**
     * @Flow\Inject
     * @var TroiKontakteService
     */
    protected TroiKontakteService $troiKontakteService;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetch(int $offset = null, int $limit = null)
    {
        return $this->troiKontakteService->getTroiContacts($offset, $limit);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->troiKontakteService->getTroiContacts());
    }
}
