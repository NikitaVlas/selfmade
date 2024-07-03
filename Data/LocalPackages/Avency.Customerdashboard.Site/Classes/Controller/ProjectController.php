<?php

namespace Avency\Customerdashboard\Site\Controller;

use Avency\Customerdashboard\Site\Services\ProjectService;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\View\JsonView;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Annotations as Flow;

class ProjectController extends ActionController
{
    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     */
    protected $supportedMediaTypes = ['application/json'];

    /**
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @Flow\Inject
     * @var ProjectService
     */
    protected ProjectService $projectService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    public function getProjectsAction(int $offset = 0, int $limit = 100)
    {
        $this->view->assign('value', $this->projectService->getProjects($offset, $limit));
    }
}
