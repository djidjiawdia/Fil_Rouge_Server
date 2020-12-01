<?php

namespace App\Repository;

use App\Entity\CommunityManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommunityManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunityManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunityManager[]    findAll()
 * @method CommunityManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunityManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunityManager::class);
    }

    // /**
    //  * @return CommunityManager[] Returns an array of CommunityManager objects
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
    public function findOneBySomeField($value): ?CommunityManager
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
