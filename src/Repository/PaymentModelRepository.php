<?php

namespace App\Repository;

use App\Entity\PaymentModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaymentModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentModel[]    findAll()
 * @method PaymentModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentModel::class);
    }

    // /**
    //  * @return PaymentModel[] Returns an array of PaymentModel objects
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
    public function findOneBySomeField($value): ?PaymentModel
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
