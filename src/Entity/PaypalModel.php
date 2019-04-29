<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaypalModelRepository")
 */
class PaypalModel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paypal_order_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderModel")
     */
    private $order_model;

    /**
     * @ORM\Column(type="json_array")
     */
    private $data_json;



    public function getId(): ?int
    {
        return $this->id;
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

    public function getPaypalOrderId(): ?string
    {
        return $this->paypal_order_id;
    }

    public function setPaypalOrderId(string $paypal_order_id): self
    {
        $this->paypal_order_id = $paypal_order_id;

        return $this;
    }

    public function getOrderModel(): ?OrderModel
    {
        return $this->order_model;
    }

    public function setOrderModel(?OrderModel $order_model): self
    {
        $this->order_model = $order_model;

        return $this;
    }

    public function getDataJson()
    {
        return $this->data_json;
    }

    public function setDataJson($data_json): self
    {
        $this->data_json = $data_json;

        return $this;
    }

}
