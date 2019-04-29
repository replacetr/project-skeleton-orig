<?php

namespace App\Repository;

use App\Entity\PaypalModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaypalModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaypalModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaypalModel[]    findAll()
 * @method PaypalModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaypalModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaypalModel::class);
    }

    // /**
    //  * @return PaypalModel[] Returns an array of PaypalModel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaypalModel
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
