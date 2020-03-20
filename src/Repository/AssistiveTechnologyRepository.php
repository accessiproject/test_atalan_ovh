<?php

namespace App\Repository;

use App\Entity\AssistiveTechnology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssistiveTechnology|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssistiveTechnology|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssistiveTechnology[]    findAll()
 * @method AssistiveTechnology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssistiveTechnologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssistiveTechnology::class);
    }

    // /**
    //  * @return AssistiveTechnology[] Returns an array of AssistiveTechnology objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssistiveTechnology
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
