<?php

namespace App\Repository;

use App\Entity\CategoryModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryModel[]    findAll()
 * @method CategoryModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryModelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryModel::class);
    }

    // /**
    //  * @return CategoryModel[] Returns an array of CategoryModel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryModel
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
