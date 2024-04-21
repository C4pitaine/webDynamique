<?php

namespace App\Repository;

use App\Entity\ExosCardio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExosCardio>
 *
 * @method ExosCardio|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExosCardio|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExosCardio[]    findAll()
 * @method ExosCardio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExosCardioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExosCardio::class);
    }

    //    /**
    //     * @return ExosCardio[] Returns an array of ExosCardio objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ExosCardio
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
