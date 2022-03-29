<?php

namespace App\Repository;

use App\Entity\IngredientsCateg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IngredientsCateg|null find($id, $lockMode = null, $lockVersion = null)
 * @method IngredientsCateg|null findOneBy(array $criteria, array $orderBy = null)
 * @method IngredientsCateg[]    findAll()
 * @method IngredientsCateg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientsCategRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IngredientsCateg::class);
    }

    // /**
    //  * @return IngredientsCateg[] Returns an array of IngredientsCateg objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IngredientsCateg
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
