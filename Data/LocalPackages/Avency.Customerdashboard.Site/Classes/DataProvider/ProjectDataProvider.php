<?php
namespace Avency\Customerdashboard\Site\DataProvider;

use Avency\Customerdashboard\Site\Services\TroiProjectService;
use Avency\Neos\Importer\DataProvider\AbstractDataProvider;
use Neos\Flow\Annotations as Flow;

class ProjectDataProvider extends AbstractDataProvider
{
    /**
     * @Flow\Inject
     * @var TroiProjectService
     */
    protected TroiProjectService $troiProjectService;

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function fetch(int $offset = null, int $limit = null)
    {
        return $this->troiProjectService->getTroiProjects($offset, $limit);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->troiProjectService->getTroiProjects());
    }
}
