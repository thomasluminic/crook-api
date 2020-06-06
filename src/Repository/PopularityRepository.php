<?php

namespace App\Repository;

use App\Entity\Popularity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Popularity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Popularity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Popularity[]    findAll()
 * @method Popularity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PopularityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Popularity::class);
    }

    // /**
    //  * @return Popularity[] Returns an array of Popularity objects
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
    public function findOneBySomeField($value): ?Popularity
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
