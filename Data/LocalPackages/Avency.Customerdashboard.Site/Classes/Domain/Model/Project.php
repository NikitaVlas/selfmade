<?php
namespace Avency\Customerdashboard\Site\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Project
{
    /**
     * @var string
     */
    protected string $projectName;

    /**
     * @var string
     */
    protected string $projectID;

    /**
     * @var string
     */
    protected string $projectNumber;

    /**
     * @var Customer|null
     * @ORM\ManyToOne(inversedBy="projects", cascade={"persist"})
     */
    protected ?Customer $customer = null;

    /**
     * @var Person|null
     * @ORM\ManyToOne(inversedBy="projects", cascade={"persist"})
     */
    protected ?Person $projectLeader = null;

    /**
     * @return string
     */
    public function getProjectName(): string
    {
        return $this->projectName;
    }

    /**
     * @param string $projectName
     * @return Project
     */
    public function setProjectName(string $projectName): Project
    {
        $this->projectName = $projectName;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectID(): string
    {
        return $this->projectID;
    }

    /**
     * @param string $projectID
     * @return Project
     */
    public function setProjectID(string $projectID): Project
    {
        $this->projectID = $projectID;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjectNumber(): string
    {
        return $this->projectNumber;
    }

    /**
     * @param string $projectNumber
     * @return Project
     */
    public function setProjectNumber(string $projectNumber): Project
    {
        $this->projectNumber = $projectNumber;
        return $this;
    }

    /**
     * Get Customer
     *
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return $this
     */
    public function setCustomer(Customer $customer): Project
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get Person
     *
     * @return Person|null
     */
    public function getProjectLeader(): ?Person
    {
        return $this->projectLeader;
    }

    /**
     * @param Person|null $projectLeader
     * @return $this
     */
    public function setProjectLeader(?Person $projectLeader): Project
    {
        $this->projectLeader = $projectLeader;
        return $this;
    }
}
