<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\Common\Collections\Collection as Collection;

/**
 * @ORM\Table(name="`lotterie`")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Lotterie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ticket;

    /**
     * @var datetime $tirage
     *
     * @ORM\Column(type="datetime")
     */
    protected $tirage;

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getTicket()
    {
        return $this->ticket;
    }

    public function setTicket(string $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getTirage()
    {
        return $this->tirage;
    }

    public function setTirage(\DateTime $date)
    {
        $this->tirage = $date;

        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }
}
