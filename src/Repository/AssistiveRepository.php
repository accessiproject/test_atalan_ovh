<?php

namespace App\Repository;

use App\Entity\Assistive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Assistive|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assistive|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assistive[]    findAll()
 * @method Assistive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssistiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assistive::class);
    }
    /*
    public function categorysupun()
    {
        select count(*) as nombre from assistive group by category_id having nombre=1
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id) as nombre')
            ->where('nombre>1')
            ->groupBy('a.category')
            ->getQuery()
            ->getResult();
    }
    */
    // /**
    
    //  * @return Assistive[] Returns an array of Assistive objects
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
    public function findOneBySomeField($value): ?Assistive
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
