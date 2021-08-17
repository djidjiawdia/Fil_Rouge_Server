<?php

namespace App\Repository;

use App\Entity\Promo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promo[]    findAll()
 * @method Promo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Promo::class);
        $this->em = $em;
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
            ->getResult();
    }

    public function findAppAttenteById($id)
    {
        $qb = $this->getEntityManager()
            ->createQuery("
                SELECT a
                FROM App\Entity\Groupe g, App\Entity\Promo p, App\Entity\Apprenant a
                WHERE g.promo = :id AND g.type = :value AND a.statut = :statut
            ")
            // JOIN App\Entity\Apprenant a ON g.apprenants = a.id
            ->setParameter('id', $id)
            ->setParameter('value', 'principal')
            ->setParameter('statut', true)
        ;

        return $qb->getResult();
        
        // return $this->createQueryBuilder('p')
        //     ->where('p.id = :valId')
        //     ->andwhere('p.statut = :stat')
        //     ->innerJoin('p.groupes', 'g')
        //     ->andwhere('g.type = :val1')
        //     ->innerJoin('g.apprenants', 'a')
        //     ->andwhere('a.statut = :val')
        //     ->setParameter('val1', 'principal')
        //     ->setParameter('valId', $id)
        //     ->setParameter('stat', false)
        //     ->setParameter('val', true)
        //     ->getQuery()
        //     ->getOneOrNullResult();
    }

    public function findByGroup($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.groupes', 'g')
            ->innerJoin('g.apprenants', 'a')
            ->andWhere('g.type = :val')
            ->andwhere('a.statut = :statut')
            ->setParameter('val', $value)
            ->setParameter('statut', false)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Promos Returns an object of Promos
     */
    public function findOneByGroup($value, $id)
    {
        $qb = $this->getEntityManager()
            ->createQuery("
                SELECT a
                FROM App\Entity\Groupe g, App\Entity\Promo p, App\Entity\Apprenant a
                WHERE g.promo = :id AND g.type = :value AND a.statut = :statut
            ")
            // JOIN App\Entity\Apprenant a ON g.apprenants = a.id
            ->setParameter('id', $id)
            ->setParameter('value', $value)
            ->setParameter('statut', false)
        ;

        return $qb->getResult();

        // return $this->createQueryBuilder('p')
        //     ->where('p.id = :id')
        //     ->setParameter('id', $id)
        //     ->addSelect('g')
        //     ->innerJoin('p.groupes', 'g')
        //     ->andWhere('g.type = :val')
        //     ->setParameter('val', 'principal')
        //     // ->innerJoin('g.apprenants', 'a')
        //     // ->andwhere('a.statut = :statut')
        //     // ->setParameter('statut', false)
        //     ->getQuery()
        //     ->getOneOrNullResult()
        // ;
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
