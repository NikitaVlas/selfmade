<?php
namespace Avency\Customerdashboard\Site\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class ContactPerson
{

    /**
     * @var string
     */
    protected string $troiContactPersonId;

    /**
     * @var string
     */
    protected string $firstName;

    /**
     * @var string
     */
    protected string $lastName;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     */
    protected ?string $email = null;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     */
    protected ?string $phone = null;

    /**
     * @var Customer|null
     * @ORM\OneToOne(mappedBy="contactPerson", cascade={"persist"})
     */
    protected ?Customer $customer = null;

    /**
     * @return string
     */
    public function getTroiContactPersonId(): string
    {
        return $this->troiContactPersonId;
    }

    /**
     * @param string $troiContactPersonId
     * @return ContactPerson
     */
    public function setTroiContactPersonId(string $troiContactPersonId): ContactPerson
    {
        $this->troiContactPersonId = $troiContactPersonId;
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
     * @return ContactPerson
     */
    public function setFirstName(string $firstName): ContactPerson
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
     * @return ContactPerson
     */
    public function setLastName(string $lastName): ContactPerson
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return ContactPerson
     */
    public function setEmail(string $email): ContactPerson
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     *
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return ContactPerson
     */
    public function setPhone(string $phone): ContactPerson
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return void
     */
    public function setCustomer(Customer $customer): Customer
    {
        $this->customer = $customer;
    }
}
