<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderDetailsModelRepository")
 */
class OrderDetailsModel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="float")
     */
    private $product_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderModel", inversedBy="order_details")
     */
    private $orderModel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductModel")
     */
    private $product_model;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getProductPrice(): ?float
    {
        return $this->product_price;
    }

    public function setProductPrice(float $product_price): self
    {
        $this->product_price = $product_price;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getOrderModel(): ?OrderModel
    {
        return $this->orderModel;
    }

    public function setOrderModel(?OrderModel $orderModel): self
    {
        $this->orderModel = $orderModel;

        return $this;
    }

    public function getProductModel(): ?ProductModel
    {
        return $this->product_model;
    }

    public function setProductModel(?ProductModel $product_model): self
    {
        $this->product_model = $product_model;

        return $this;
    }


}
