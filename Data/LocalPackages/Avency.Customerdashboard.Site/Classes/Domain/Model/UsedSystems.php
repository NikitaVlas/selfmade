<?php

namespace Avency\Customerdashboard\Site\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class UsedSystems
{
    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $usedVersion = null;

    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $cookie = null;

    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $trackingsTools = null;

    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $sslCertificate = null;

    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $urlLocal = null;

    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $urlPreview = null;

    /**
     * @ORM\Column(nullable="true")
     * @var string|null
     */
    protected ?string $urlLive = null;

    /**
     * @ORM\Column(nullable="true")
     * @var Customer|null
     * @ORM\ManyToOne(inversedBy="usedSystems", cascade={"persist"})
     */
    protected ?Customer $customer = null;

    /**
     * @ORM\Column(nullable="true")
     * @var Person|null
     * @ORM\ManyToOne(inversedBy="usedSystemsAsProductManager", cascade={"persist"})
     */
    protected ?Person $productManager = null;

    /**
     * @ORM\Column(nullable="true")
     * @var ?Person|null
     * @ORM\ManyToOne (inversedBy="usedSystemsAsLeadDev", cascade={"persist"})
     */
    protected ?Person $leadDev = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return UsedSystems
     */
    public function setTitle(?string $title): UsedSystems
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsedVersion(): ?string
    {
        return $this->usedVersion;
    }

    /**
     * @param string|null $usedVersion
     * @return UsedSystems
     */
    public function setUsedVersion(?string $usedVersion): UsedSystems
    {
        $this->usedVersion = $usedVersion;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCookie(): ?string
    {
        return $this->cookie;
    }

    /**
     * @param string|null $cookie
     * @return UsedSystems
     */
    public function setCookie(?string $cookie): UsedSystems
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTrackingsTools(): ?string
    {
        return $this->trackingsTools;
    }

    /**
     * @param string|null $trackingsTools
     * @return UsedSystems
     */
    public function setTrackingsTools(?string $trackingsTools): UsedSystems
    {
        $this->trackingsTools = $trackingsTools;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSslCertificate(): ?string
    {
        return $this->sslCertificate;
    }

    /**
     * @param string|null $sslCertificate
     * @return UsedSystems
     */
    public function setSslCertificate(?string $sslCertificate): UsedSystems
    {
        $this->sslCertificate = $sslCertificate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlLocal(): ?string
    {
        return $this->urlLocal;
    }

    /**
     * @param string|null $urlLocal
     * @return UsedSystems
     */
    public function setUrlLocal(?string $urlLocal): UsedSystems
    {
        $this->urlLocal = $urlLocal;
        return  $this;
    }

    /**
     * @return string|null
     */
    public function getUrlPreview(): ?string
    {
        return $this->urlPreview;
    }

    /**
     * @param string|null $urlPreview
     * @return UsedSystems
     */
    public function setUrlPreview(?string $urlPreview): UsedSystems
    {
        $this->urlPreview = $urlPreview;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlLive(): ?string
    {
        return $this->urlLive;
    }

    /**
     * @param string|null $urlLive
     * @return UsedSystems
     */
    public function setUrlLive(?string $urlLive): UsedSystems
    {
        $this->urlLive = $urlLive;
        return $this;
    }

    /**
     * Get all Customers
     *
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return $this
     */
    public function setCustomer(?Customer $customer): UsedSystems
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getProductManager(): ?Person
    {
        return $this->productManager;
    }

    /**
     * @return Person|null
     */
    public function getLeaDev(): ?Person
    {
        return $this->leadDev;
    }

    /**
     * @param Person|null $productManager
     * @return UsedSystems
     */
    public function setProductManager(?Person $productManager): UsedSystems
    {
        $this->productManager = $productManager;
        return $this;
    }

    /**
     * @param Person|null $leadDev
     * @return UsedSystems
     */
    public function setLeadDev(?Person $leadDev): UsedSystems
    {

        $this->leadDev = $leadDev;
        return $this;
    }
}
