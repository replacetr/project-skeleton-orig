<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentModelRepository")
 */
class PaymentModel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $payment_datetime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payer_email;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OrderModel", mappedBy="payment_model", cascade={"persist", "remove"})
     */
    private $orderModel;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    public function setPaymentType(string $payment_type): self
    {
        $this->payment_type = $payment_type;

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



    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(string $payment_status): self
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    public function getPayerEmail(): ?string
    {
        return $this->payer_email;
    }

    public function setPayerEmail(string $payer_email): self
    {
        $this->payer_email = $payer_email;

        return $this;
    }

    public function getPaymentDatetime(): ?string
    {
        return $this->payment_datetime;
    }

    public function setPaymentDatetime(?string $payment_datetime): self
    {
        $this->payment_datetime = $payment_datetime;

        return $this;
    }

    public function getOrderModel(): ?OrderModel
    {
        return $this->orderModel;
    }

    public function setOrderModel(?OrderModel $orderModel): self
    {
        $this->orderModel = $orderModel;

        // set (or unset) the owning side of the relation if necessary
        $newPayment_model = $orderModel === null ? null : $this;
        if ($newPayment_model !== $orderModel->getPaymentModel()) {
            $orderModel->setPaymentModel($newPayment_model);
        }

        return $this;
    }






}
