<?php

namespace Avency\Customerdashboard\Site\Controller;

use Avency\Customerdashboard\Site\Domain\Model\Customer;
use Avency\Customerdashboard\Site\Domain\Model\Person;
use Avency\Customerdashboard\Site\Domain\Model\UsedSystems;
use Avency\Customerdashboard\Site\Services\PersonService;
use Avency\Customerdashboard\Site\Services\UsedSystemsService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\View\JsonView;

class UsedSystemsController extends ActionController
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
     * @var UsedSystemsService
     */
    protected UsedSystemsService $usedSystemsService;

    /**
     * @Flow\Inject
     * @var PersonService
     */
    protected PersonService $personService;

    /**
     * @param string|null $title
     * @param string|null $usedVersion
     * @param string|null $cookie
     * @param string|null $trackingsTools
     * @param string|null $sslCertificate
     * @param string|null $urlLocal
     * @param string|null $urlPreview
     * @param string|null $urlLive
     * @param Customer|null $customer
     * @param Person|null $productManager
     * @param Person|null $leadDev
     * @return void
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function addUsedSystemAction(?string $title = null, ?string $usedVersion = null, ?string $cookie = null, ?string $trackingsTools = null, ?string $sslCertificate = null, ?string $urlLocal = null, ?string $urlPreview = null, ?string $urlLive = null, ?Customer $customer = null, ?Person $productManager = null, ?Person $leadDev = null)
    {
        $this->view->assign('value', $this->usedSystemsService->addNewUsedSystem($title, $usedVersion, $cookie, $trackingsTools, $sslCertificate, $urlLocal, $urlPreview, $urlLive, $customer, $productManager, $leadDev));
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return void
     */
    public function getUsedSystemsAction(int $offset = 0, int $limit = 100,)
    {
        $this->view->assign('value', $this->usedSystemsService->getUsedSystems($offset, $limit));
    }

    /**
     * @param UsedSystems $usedSystems
     * @return void
     */
    public function getUsedSystemAction(UsedSystems $usedSystems)
    {
        $this->view->assign('value', $this->usedSystemsService->getUsedSystem($usedSystems));
    }

    /**
     * @param UsedSystems $usedSystems
     * @param string|null $title
     * @param string|null $usedVersion
     * @param string|null $cookie
     * @param string|null $trackingsTools
     * @param string|null $sslCertificate
     * @param string|null $urlLocal
     * @param string|null $urlPreview
     * @param string|null $urlLive
     * @param Person|null $productManager
     * @param Person|null $leadDev
     * @return void
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function updateUsedSystemAction(UsedSystems $usedSystems, ?string $title = null, ?string $usedVersion = null, ?string $cookie = null, ?string $trackingsTools = null, ?string $sslCertificate = null, ?string $urlLocal = null, ?string $urlPreview = null, ?string $urlLive = null, ?Person $productManager = null, ?Person $leadDev = null)
    {
        $this->view->assign('value', $this->usedSystemsService->updateUsedSystem($usedSystems, $title, $usedVersion, $cookie, $trackingsTools, $sslCertificate, $urlLocal, $urlPreview, $urlLive, $productManager, $leadDev));
    }
}
