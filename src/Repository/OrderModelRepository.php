<?php

namespace App\Repository;

use App\Entity\OrderModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderModel[]    findAll()
 * @method OrderModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderModel::class);
    }

    // /**
    //  * @return OrderModel[] Returns an array of OrderModel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderModel
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
