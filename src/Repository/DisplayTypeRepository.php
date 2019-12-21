<?php

namespace App\Repository;

use App\Entity\DisplayType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DisplayType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DisplayType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DisplayType[]    findAll()
 * @method DisplayType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisplayTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DisplayType::class);
    }

    // /**
    //  * @return DisplayType[] Returns an array of DisplayType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DisplayType
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
