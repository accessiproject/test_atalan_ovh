<?php

namespace App\Repository;

use App\Entity\TechnicalComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TechnicalComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechnicalComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechnicalComponent[]    findAll()
 * @method TechnicalComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnicalComponentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechnicalComponent::class);
    }

    // /**
    //  * @return TechnicalComponent[] Returns an array of TechnicalComponent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TechnicalComponent
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
