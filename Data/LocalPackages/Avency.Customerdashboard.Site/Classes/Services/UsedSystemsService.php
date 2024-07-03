<?php

namespace Avency\Customerdashboard\Site\Services;

use Avency\Customerdashboard\Site\Domain\Model\Customer;
use Avency\Customerdashboard\Site\Domain\Model\Person;
use Avency\Customerdashboard\Site\Domain\Model\UsedSystems;
use Avency\Customerdashboard\Site\Domain\Repository\UsedSystemsRepository;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Annotations as Flow;

/**
 * UsedSystemsService
 */
class UsedSystemsService
{

    /**
     * @Flow\Inject
     * @var UsedSystemsRepository
     */
    protected UsedSystemsRepository $usedSystemsRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected PersistenceManagerInterface $persistenceManager;

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
     * @return array
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function addNewUsedSystem(?string $title = null, ?string $usedVersion = null, ?string $cookie = null, ?string $trackingsTools = null, ?string $sslCertificate = null, ?string $urlLocal = null, ?string $urlPreview = null, ?string $urlLive = null, ?Customer $customer = null, ?Person $productManager = null, ?Person $leadDev = null)
    {
        $usedSystem = new UsedSystems();
        $usedSystem->setTitle($title);
        $usedSystem->setUsedVersion($usedVersion);
        $usedSystem->setCookie($cookie);
        $usedSystem->setTrackingsTools($trackingsTools);
        $usedSystem->setSslCertificate($sslCertificate);
        $usedSystem->setUrlLocal($urlLocal);
        $usedSystem->setUrlPreview($urlPreview);
        $usedSystem->setUrlLive($urlLive);
        $usedSystem->setCustomer($customer);
        $usedSystem->setProductManager($productManager);
        $usedSystem->setLeadDev($leadDev);

        $this->usedSystemsRepository->add($usedSystem);

        $response = [
            'title' => $title,
            'usedVersion' => $usedVersion,
            'cookie' => $cookie,
            'trackingsTools' => $trackingsTools,
            'sslCertificate' => $sslCertificate,
            'urlLocal' => $urlLocal,
            'urlPreview' => $urlPreview,
            'urlLive' => $urlLive,
            'customer' => [],
            'productManager' => [],
            'leadDev' => [],
        ];

        if ($customer instanceof Customer) {
            $response['customer'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($customer),
                'title' => $customer->getTitle(),
            ];
        }

        if ($productManager instanceof Person) {
            $response['productManager'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($productManager),
                'firstName' => $productManager->getFirstName(),
                'lastName' => $productManager->getLastName(),
            ];
        }

        if ($leadDev instanceof Person) {
            $response['leadDev'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($leadDev),
                'firstName' => $leadDev->getFirstName(),
                'lastName' => $leadDev->getLastName(),
            ];
        }

        return $response;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getUsedSystems(int $offset = 0, int $limit = 100): array
    {
        $usedSystems = $this->usedSystemsRepository->findAll()->getQuery()->setLimit($limit)->setOffset($offset)->execute();

        $results = [];
        foreach ($usedSystems as $usedSystem) {
            $customerName = "";
            $productManagerFirstName = '';
            $productManagerLastName = '';
            $leadDevFirstName = '';
            $leadDevLatName = '';

            if ($usedSystem->getCustomer() instanceof Customer) {
                $customerName = $usedSystem->getCustomer()->getTitle();
            }

            if ($usedSystem->getProductManager() instanceof Person) {
                $productManagerFirstName = $usedSystem->getProductManager()->getFirstName();
            }

            if ($usedSystem->getProductManager() instanceof Person) {
                $productManagerLastName = $usedSystem->getProductManager()->getLastName();
            }

            if ($usedSystem->getLeaDev() instanceof Person) {
                $leadDevFirstName = $usedSystem->getLeaDev()->getFirstName();
            }

            if ($usedSystem->getLeaDev() instanceof Person) {
                $leadDevLatName = $usedSystem->getLeaDev()->getLastName();
            }

            $results[] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($usedSystem),
                'title' => $usedSystem->getTitle(),
                'usedVersion' => $usedSystem->getUsedVersion(),
                'cookie' => $usedSystem->getCookie(),
                'trackingsTools' => $usedSystem->getTrackingsTools(),
                'sslCertificate' => $usedSystem->getSslCertificate(),
                'urlLocal' => $usedSystem->getUrlLocal(),
                'urlPreview' => $usedSystem->getUrlPreview(),
                'urlLive' => $usedSystem->getUrlLive(),
                'customer' => [
                    'title' => $customerName,
                ],
                'productManager' => [
                    'firstName' => $productManagerFirstName,
                    'lastName' => $productManagerLastName,
                ],
                'leadDev' => [
                    'firstName' => $leadDevFirstName,
                    'lastName' => $leadDevLatName,
                ],
            ];
        }

        return $results;
    }

    /**
     * @param UsedSystems $usedSystems
     * @return array
     */
    public function getUsedSystem(UsedSystems $usedSystems)
    {
        $results = [
            'identifier' => $this->persistenceManager->getIdentifierByObject($usedSystems),
            'title' => $usedSystems->getTitle(),
            'usedVersion' => $usedSystems->getUsedVersion(),
            'cookie' => $usedSystems->getCookie(),
            'trackingsTools' => $usedSystems->getTrackingsTools(),
            'sslCertificate' => $usedSystems->getSslCertificate(),
            'urlLocal' => $usedSystems->getUrlLocal(),
            'urlPreview' => $usedSystems->getUrlPreview(),
            'urlLive' => $usedSystems->getUrlLive(),
            'customer' => [],
            'productManager' => null,
            'leadDev' => null,
        ];

        if ($usedSystems->getCustomer() instanceof Customer) {
            $results['customer'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($usedSystems->getCustomer()),
                'title' => $usedSystems->getCustomer()->getTitle(),
            ];
        }

        if ($usedSystems->getProductManager() instanceof Person) {
            $results['productManager'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($usedSystems->getProductManager()),
                'firstname' => $usedSystems->getProductManager()->getFirstName(),
                'lastname' => $usedSystems->getProductManager()->getLastName(),
            ];
        }

        if ($usedSystems->getLeaDev() instanceof Person) {
            $results['leadDev'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($usedSystems->getLeaDev()),
                'firstname' => $usedSystems->getLeaDev()->getFirstName(),
                'lastname' => $usedSystems->getLeaDev()->getLastName(),
            ];
        }

        return $results;
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
     * @return array
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function updateUsedSystem(UsedSystems $usedSystems, ?string $title = null, ?string $usedVersion = null, ?string $cookie = null, ?string $trackingsTools = null, ?string $sslCertificate = null, ?string $urlLocal = null, ?string $urlPreview = null, ?string $urlLive = null, ?Person $productManager = null, ?Person $leadDev = null)
    {
        $usedSystems->setTitle($title);
        $usedSystems->setUsedVersion($usedVersion);
        $usedSystems->setCookie($cookie);
        $usedSystems->setTrackingsTools($trackingsTools);
        $usedSystems->setSslCertificate($sslCertificate);
        $usedSystems->setUrlLocal($urlLocal);
        $usedSystems->setUrlPreview($urlPreview);
        $usedSystems->setUrlLive($urlLive);
        $usedSystems->setProductManager($productManager);
        $usedSystems->setLeadDev($leadDev);
        $this->usedSystemsRepository->update($usedSystems);

        $results = [
            'title' => $title,
            'usedVersion' => $usedVersion,
            'cookie' => $cookie,
            'trackingsTools' => $trackingsTools,
            'sslCertificate' => $sslCertificate,
            'urlLocal' => $urlLocal,
            'urlPreview' => $urlPreview,
            'urlLive' => $urlLive,
            'productManager' => null,
            'leadDev' => null,
        ];

        if ($usedSystems->getProductManager() instanceof Person) {
            $results['productManager'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($usedSystems->getProductManager()),
                'firstname' => $usedSystems->getProductManager()->getFirstName(),
                'lastname' => $usedSystems->getProductManager()->getLastName(),
            ];
        }

        if ($usedSystems->getLeaDev() instanceof Person) {
            $results['leadDev'] = [
                'identifier' => $this->persistenceManager->getIdentifierByObject($usedSystems->getLeaDev()),
                'firstname' => $usedSystems->getLeaDev()->getFirstName(),
                'lastname' => $usedSystems->getLeaDev()->getLastName(),
            ];
        }

        return $results;
    }
}
