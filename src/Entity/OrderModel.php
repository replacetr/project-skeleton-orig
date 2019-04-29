<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderModelRepository")
 */
class OrderModel
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
    private $order_total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderDetailsModel", mappedBy="orderModel")
     */
    private $order_details;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $customer;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PaymentModel", inversedBy="orderModel", cascade={"persist", "remove"})
     */
    private $payment_model;

    public function __construct()
    {
        $this->order_details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderTotal(): ?float
    {
        return $this->order_total;
    }

    public function setOrderTotal(float $order_total): self
    {
        $this->order_total = $order_total;

        return $this;
    }

    /**
     * @return Collection|OrderDetailsModel[]
     */
    public function getOrderDetails(): Collection
    {
        return $this->order_details;
    }

    public function addOrderDetail(OrderDetailsModel $orderDetail): self
    {
        if (!$this->order_details->contains($orderDetail)) {
            $this->order_details[] = $orderDetail;
            $orderDetail->setOrderModel($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetailsModel $orderDetail): self
    {
        if ($this->order_details->contains($orderDetail)) {
            $this->order_details->removeElement($orderDetail);
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrderModel() === $this) {
                $orderDetail->setOrderModel(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getPaymentModel(): ?PaymentModel
    {
        return $this->payment_model;
    }

    public function setPaymentModel(?PaymentModel $payment_model): self
    {
        $this->payment_model = $payment_model;

        return $this;
    }
}
