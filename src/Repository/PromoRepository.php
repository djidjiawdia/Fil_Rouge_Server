<?php

namespace App\Repository;

use App\Entity\Promo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promo::class);
    }

    public function findAppAttente()
    {
        return $this->createQueryBuilder('p')
            ->where('p.statut = :stat')
            ->innerJoin('p.groupes', 'g')
            ->innerJoin('g.apprenants', 'a')
            ->andwhere('g.type = :val1')
            ->andwhere('a.statut = :val')
            ->setParameter('stat', false)
            ->setParameter('val1', 'principal')
            ->setParameter('val', true)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAppAttenteById($id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :valId')
            ->andwhere('p.statut = :stat')
            ->innerJoin('p.groupes', 'g')
            ->andwhere('g.type = :val1')
            ->innerJoin('g.apprenants', 'a')
            ->andwhere('a.statut = :val')
            ->setParameter('val1', 'principal')
            ->setParameter('valId', $id)
            ->setParameter('stat', false)
            ->setParameter('val', false)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByGroup($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.groupes', 'g')
            ->andWhere('g.type = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Promos Returns an object of Promos
     */
    public function findOneByGroup($value, $id)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.groupes', 'g')
            ->andWhere('g.type = :val')
            ->andWhere('p.id = :id')
            ->setParameter('val', $value)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Promo[] Returns an array of Promo objects
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
    public function findOneBySomeField($value): ?Promo
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
