<?php
namespace Avency\Customerdashboard\Site\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Customer
{
    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $abbreviation;

    /**
     * @var int
     */
    protected int $customerNumber;

    /**
     * @ORM\Column(nullable="true")
     * @var int|null
     */
    protected ?int $priority = null;

    /**
     * @var string
     */
    protected string $troiIdKunde;


    /**
     * @ORM\Column(nullable="true")
     * @var Person|null
     * @ORM\ManyToOne(inversedBy="customer", cascade={"persist"})
     */
    protected ?Person $productManagerDefault = null;

    /**
     * @var ContactPerson
     * @ORM\OneToOne(inversedBy="customer", cascade={"persist"})
     */
    protected $contactPerson;

    /**
     * @var Collection<UsedSystems>
     * @ORM\OneToMany(mappedBy="customer", cascade={"persist"})
     */
    protected Collection $usedSystems;

    /**
     * @var Collection<Project>
     * @ORM\OneToMany(mappedBy="customer", cascade={"persist"})
     */
    protected Collection $projects;

    public function __construct() {
        $this->usedSystems = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Customer
     */
    public function setTitle(string $title): Customer
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    /**
     * @param string $abbreviation
     * @return Customer
     */
    public function setAbbreviation(string $abbreviation): Customer
    {
         $this->abbreviation = $abbreviation;
         return $this;
    }

    /**
     * @return int
     */
    public function getCustomerNumber(): int
    {
        return $this->customerNumber;
    }

    /**
     * @param int $customerNumber
     * @return Customer
     */
    public function setCustomerNumber(int $customerNumber): Customer
    {
        $this->customerNumber = $customerNumber;
        return $this;
    }

    /**
     * @return ContactPerson|null
     */
    public function getContactPerson(): ?ContactPerson
    {
        return $this->contactPerson;
    }

    /**
     * @param ContactPerson $contactPerson
     * @return Customer
     */
    public function setContactPerson(ContactPerson $contactPerson): Customer
    {
        $this->contactPerson = $contactPerson;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }

    /**
     * @param int|null $priority
     * @return Customer
     */
    public function setPriority(?int $priority): Customer
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * get all UsedSystems
     *
     * @return Collection<UsedSystems>
     */
    public function getUsedSystems(): Collection
    {
        return $this->usedSystems;
    }

    /**
     * add UsedSystems to the Customer
     *
     * @param UsedSystems $usedSystem
     * @return $this
     */
    public function addUsedSystems(UsedSystems $usedSystem): Customer
    {
        $this->usedSystems->add($usedSystem);
        return $this;
    }

    /**
     * @return Collection<Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @param Project $projects
     * @return Customer
     */
    public function addProjects(Project $projects): Customer
    {
        $this->projects->add($projects);
        return $this;
    }

    /**
     * get all $productManagerDefault
     *
     * @return Person|null
     */
    public function getProductManagerDefault(): ?Person
    {
        return $this->productManagerDefault;
    }

    /**
     * @param Person|null $productManagerDefault
     * @return Customer
     */
    public function setProductManagerDefault(?Person $productManagerDefault): Customer
    {
        $this->productManagerDefault = $productManagerDefault;
        return $this;
    }

    /**
     * @return string
     */
    public function getTroiIdKunde(): string
    {
        return $this->troiIdKunde;
    }

    /**
     * @param string $troiIdKunde
     */
    public function setTroiIdKunde(string $troiIdKunde): void
    {
        $this->troiIdKunde = $troiIdKunde;
    }
}
