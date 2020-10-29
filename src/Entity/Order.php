<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\Common\Collections\Collection as Collection;

/**
 * @ORM\Table(name="`order`", options={"collate"="utf8_unicode_ci"})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Order
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
     * @ORM\Column(type="string", length=255, required=false)
     */
    private $email;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=20, required=false)
     */
    private $tel;

    /**
     * @var datetime $livraison
     *
     * @ORM\Column(type="datetime")
     */
    protected $livraison;

    /**
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="order", fetch="EXTRA_LAZY")
     */
    private $productsOrder;

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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function setTel(string $tel)
    {
        $this->tel = $tel;

        return $this;
    }

    public function getLivraison()
    {
        return $this->livraison;
    }

    public function setLivraison(\DateTime $livraison)
    {
        $this->livraison = $livraison;

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
