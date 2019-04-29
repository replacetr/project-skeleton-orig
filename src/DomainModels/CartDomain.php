<?php
namespace App\DomainModels;
use App\Entity\ProductModel;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CartModel;
use Doctrine\Common\Persistence\ManagerRegistry;

class CartDomain
{
    private $em;
    private $manager;
    public function __construct(EntityManagerInterface $em, ManagerRegistry $manager)
    {
        $this->em = $em;
        $this->manager = $manager;
    }


    public function addCart($userId, $productId)
    {
        $check = $this->em->getRepository(CartModel::class)->findByCart($userId, $productId);


        if (empty($check)) {
            $products = $this->em->getRepository(ProductModel::class)->find($productId);
            $cart = new CartModel();
            $cart->setProductId($products->getId());
            $cart->setProductImage($products->getProductImage());
            $cart->setQty(1); // this 1 of the product set
            $cart->setUserId($userId);
            $priceQty = $products->getProductPrice() * 1;
            $cart->setPriceQty($priceQty);
            $cart->setCartPrice($products->getProductPrice());
            $cart->setProductName($products->getProductName());
            $entityManager = $this->manager->getManager();
            $entityManager->persist($cart);
            $entityManager->flush();
            $newcart = $this->em->getRepository(CartModel::class)->findByUserId($userId);
            return $newcart;
        } else {
            $entityManager  = new CartModel();
            $entityManager = $this->manager->getManager();
            $product = $entityManager->getRepository(CartModel::class)->findByCart($userId, $productId);
            $qty = $product->getQty() + 1;
            $product->setPriceQty($product->getCartPrice() * $qty);
            $product->setQty($qty);
            $entityManager->flush();
            $newcart = $this->em->getRepository(CartModel::class)->findByUserId($userId);
            return $newcart;
        }
    }

    public function updateCart($qty,$userId,$productId)
    {
        if($qty > 0){
        $entityManager  = new CartModel();
        $entityManager = $this->manager->getManager();
        $product = $entityManager->getRepository(CartModel::class)->findByCart($userId, $productId);
        $product->setPriceQty($product->getCartPrice() * $qty);
        $product->setQty($qty);
        $entityManager->flush();
        $this->em->getRepository(CartModel::class)->findByUserId($userId);
        return true;

        }
        else{
            $this->removeCart($userId,$productId);
            return true;
        }



    }
    public function removeCart($userId,$productId){
      $entityManager  = new CartModel();
      $entityManager = $this->manager->getManager();
      $product = $entityManager->getRepository(CartModel::class)->findByCart($userId,$productId);
      $entityManager->remove($product);
      $entityManager->flush();
      $newcart = $this->em->getRepository(CartModel::class)->findByUserId($userId);
      return $newcart;
    }





}
