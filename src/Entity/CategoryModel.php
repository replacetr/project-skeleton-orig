<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryModelRepository")
 */
class CategoryModel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category_name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductModel", mappedBy="category")
     */
    private $product_models;

    public function __construct()
    {
        $this->product_models = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): self
    {
        $this->category_name = $category_name;

        return $this;
    }

    /**
     * @return Collection|ProductModel[]
     */
    public function getProductModels(): Collection
    {
        return $this->product_models;
    }

    public function addProductModel(ProductModel $productModel): self
    {
        if (!$this->product_models->contains($productModel)) {
            $this->product_models[] = $productModel;
            $productModel->setCategory($this);
        }

        return $this;
    }

    public function removeProductModel(ProductModel $productModel): self
    {
        if ($this->product_models->contains($productModel)) {
            $this->product_models->removeElement($productModel);
            // set the owning side to null (unless already changed)
            if ($productModel->getCategory() === $this) {
                $productModel->setCategory(null);
            }
        }

        return $this;
    }

}
