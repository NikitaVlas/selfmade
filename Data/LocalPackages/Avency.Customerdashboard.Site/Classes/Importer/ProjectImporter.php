<?php
namespace Avency\Customerdashboard\Site\Importer;


use Avency\Customerdashboard\Site\Domain\Model\Project;
use Avency\Customerdashboard\Site\Domain\Repository\ProjectRepository;
use Avency\Customerdashboard\Site\Services\ProjectService;
use Avency\Neos\Importer\Importer\AbstractImporter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;

class ProjectImporter extends AbstractImporter
{
    /**
     * @Flow\Inject
     * @var ProjectService
     */
    protected ProjectService $projectService;

    /**
     * @Flow\Inject
     * @var ProjectRepository
     */
    protected ProjectRepository $projectRepository;

    protected array $usedProjectsIds = [];

    /**
     * @param array $batch
     */
    public function import(array $batch)
    {
        if ($batch['deleted'] !== 0) {
            return;
        }

        $this->projectService->addOrUpdateProject(
            $batch['identifier'],
            $batch['projectname'],
            $batch['projectnumber'],
            $batch['customerId'],
            $batch['projectLeader']
        );

        $this->usedProjectsIds[] = $batch['identifier'];
    }

    /**
     * Delete unused project
     *
     * ToDo: call this function after import
     *
     * @return void
     * @throws IllegalObjectTypeException
     */
    public function cleanup(): void
    {
        $projects = $this->projectRepository->findAll();
        /** @var Project $project */
        foreach ($projects as $project) {
            if (!in_array($project->getProjectID(), $this->usedProjectsIds)) {
                $this->projectRepository->remove($project);
            }
        }
    }
}
