<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartModelRepository")
 */
class CartModel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $product_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $qty;

    /**
     * @ORM\Column(type="float")
     */
    private $cart_price;

    /**
     * @ORM\Column(type="float")
     */
    private $price_qty;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_name;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): self
    {
        $this->product_id = $product_id;

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

    public function getCartPrice(): ?float
    {
        return $this->cart_price;
    }

    public function setCartPrice(float $cart_price): self
    {
        $this->cart_price = $cart_price;

        return $this;
    }

    public function getPriceQty(): ?float
    {
        return $this->price_qty;
    }

    public function setPriceQty(float $price_qty): self
    {
        $this->price_qty = $price_qty;

        return $this;
    }

    public function getProductImage(): ?string
    {
        return $this->product_image;
    }

    public function setProductImage(string $product_image): self
    {
        $this->product_image = $product_image;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }
}
