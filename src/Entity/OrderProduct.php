<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
/**
 * Product Relation order
 *
 * @ORM\Table(name="order_product")
 * @ORM\Entity
 */
class OrderProduct
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="products")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     */
    protected $order;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="orders")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    protected $product;

    /**
     * @ORM\Column(name="amount", type="integer", nullable=false, options={"default": 1})
     */
    protected $amount;


    public function setOrder(Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setAmount($amount)
    {
        $this->amount = (int)$amount;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}