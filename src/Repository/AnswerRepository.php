<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function findSelectResult($survey,$param)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.survey = :survey')
            ->setParameter('survey', $survey)
            ->groupBy($param)
            ->getQuery()
            ->getArrayResult();
    }
    
    public function findSelectResults($survey,$id,$device_type,$os_name,$browser_name,$p_id)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT a FROM App\Entity\Answer a JOIN a.propositions p WHERE p.id=$p_id AND a.survey=$survey AND a.id=$id AND a.device_type=$device_type and a.os_name=$os_name and a.browser_name=$browser_name");
        $results = $query->getResult();
        return $results;
    }
    
    // /**
    //  * @return Answer[] Returns an array of Answer objects
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
    public function findOneBySomeField($value): ?Answer
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
