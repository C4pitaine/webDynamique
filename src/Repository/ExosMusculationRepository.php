<?php

namespace App\Repository;

use App\Entity\ExosMusculation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExosMusculation>
 *
 * @method ExosMusculation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExosMusculation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExosMusculation[]    findAll()
 * @method ExosMusculation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExosMusculationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExosMusculation::class);
    }

    //    /**
    //     * @return ExosMusculation[] Returns an array of ExosMusculation objects
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

    //    public function findOneBySomeField($value): ?ExosMusculation
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
