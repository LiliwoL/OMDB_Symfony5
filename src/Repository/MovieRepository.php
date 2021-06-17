<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }


    /**
     * Returns a QueryBuilder to retrieve movies without censorship to get used in a FormType
     * 
     * @return QueryBuilder
     */
    public function findByEmptyCensorshipQueryBuilder()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.censure IS NULL')
            ->orderBy('m.id', 'ASC')
        ;
    }

    /**
     * Returns a QueryBuilder to retireve movies without censorship
     * 
     * @return Movies[] Returns an array of Movies
     */
    public function findByEmptyCensorship()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.censure IS NULL')
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
