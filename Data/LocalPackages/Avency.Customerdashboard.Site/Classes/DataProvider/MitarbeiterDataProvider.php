<?php
namespace Avency\Customerdashboard\Site\DataProvider;

use Avency\Customerdashboard\Site\Services\TroiMitarbeiterService;
use Avency\Neos\Importer\DataProvider\AbstractDataProvider;
use Neos\Flow\Annotations as Flow;

class MitarbeiterDataProvider extends AbstractDataProvider
{
    /**
     * @Flow\Inject
     * @var TroiMitarbeiterService
     */
    protected TroiMitarbeiterService $troiMitarbeiterService;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetch(int $offset = null, int $limit = null)
    {
        return $this->troiMitarbeiterService->getTroiMitarbeiter($offset, $limit);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->troiMitarbeiterService->getTroiMitarbeiter());
    }
}
