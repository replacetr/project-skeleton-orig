<?php
namespace App\DomainModels;

use Doctrine\ORM\EntityManagerInterface;

class ProductDomain {
    Protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // public function exttt(){
        // $hello = new CartDomain();
        // $hello->gila($this->em);
    // }
  

}