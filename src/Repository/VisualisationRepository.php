<?php

namespace App\Repository;

use App\Entity\Visualisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visualisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visualisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visualisation[]    findAll()
 * @method Visualisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisualisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visualisation::class);
    }

    // /**
    //  * @return Visualisation[] Returns an array of Visualisation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visualisation
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
