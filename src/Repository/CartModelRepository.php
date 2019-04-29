<?php

namespace App\Repository;

use App\Entity\CartModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
/**
 * @method CartModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartModel[]    findAll()
 * @method CartModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CartModel::class);
    }

    // /**
    //  * @return CartModel[] Returns an array of CartModel objects
    //  */

    public function findByUserId($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCart($user,$product)
    {
        return $this->createQueryBuilder('c')
                 ->where('c.user_id = :user AND c.product_id = :product')
                 ->setParameters(new ArrayCollection(array(
                     new Parameter('user', $user),
                     new Parameter('product', $product)
                )))
                ->getQuery()
                ->getOneOrNullResult()
                ;
    }

    public function cartTotal($user)
{
    return $this->createQueryBuilder('c')
    ->andwhere('c.user_id = :user')
    ->setParameter('user' , $user)
    ->select('SUM(c.price_qty) as total')
        ->getQuery()
        ->getSingleScalarResult();
}



    /*
    public function findOneBySomeField($value): ?CartModel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
