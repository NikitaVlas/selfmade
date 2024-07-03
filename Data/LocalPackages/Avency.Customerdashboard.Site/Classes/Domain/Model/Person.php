<?php
namespace Avency\Customerdashboard\Site\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Person
{
    /**
     * @var string
     */
    protected $troiIdPerson;

    /**
     * @var string
     */
    protected string $firstName;

    /**
     * @var string
     */
    protected string $lastName;

    /**
     * @var string
     */
    protected string $abbreviationPerson;

    /**
     * @var Collection<Customer>
     * @ORM\OneToMany(mappedBy="productManagerDefault", cascade={"persist"})
     */
    protected Collection $customers;

    /**
     * @var Collection<UsedSystems>
     * @ORM\OneToMany(mappedBy="productManager", cascade={"persist"})
     */
    protected Collection $usedSystemsAsProductManager;

    /**
     * @var Collection<UsedSystems>
     * @ORM\OneToMany(mappedBy="leadDev", cascade={"persist"})
     */
    protected Collection $usedSystemsAsLeadDev;

    /**
     * @var Collection<Project>
     * @ORM\OneToMany(mappedBy="projectLeader", cascade={"persist"})
     */
    protected Collection $projects;

    public function __construct() {
        $this->customers = new ArrayCollection();
        $this->usedSystemsAsProductManager = new ArrayCollection();
        $this->usedSystemsAsLeadDev = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTroiIdPerson(): string
    {
        return $this->troiIdPerson;
    }

    /**
     * @param string $troiIdPerson
     * @return Person
     */
    public function setTroiIdPerson(string $troiIdPerson): Person
    {
        $this->troiIdPerson = $troiIdPerson;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Person
     */
    public function setFirstName(string $firstName): Person
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Person
     */
    public function setLastName(string $lastName): Person
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbbreviationPerson(): string
    {
        return $this->abbreviationPerson;
    }

    /**
     * @param string $abbreviationPerson
     * @return Person
     */
    public function setAbbreviationPerson(string $abbreviationPerson): Person
    {
        $this->abbreviationPerson = $abbreviationPerson;
        return $this;
    }

    /**
     * get all Customers
     *
     * @return Collection<Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    /**
     * add Customer to the Person
     *
     * @param Customer $customer
     * @return $this
     */
    public function addCustomer(Customer $customer): Person
    {
        $this->customers->add($customer);
        return $this;
    }

    /**
     * @return Collection<UsedSystems>
     */
    public function getUsedSystemsAsProductManager(): Collection
    {
        return $this->usedSystemsAsProductManager;
    }

    /**
     * @return Collection<UsedSystems>
     */
    public function getUsedSystemsAsLeadDev(): Collection
    {
        return $this->usedSystemsAsLeadDev;
    }

    /**
     * @return Collection
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @param Collection $projects
     * @return $this
     */
    public function setProjects(Collection $projects): Person
    {
        $this->projects = $projects;
        return $this;
    }
}
