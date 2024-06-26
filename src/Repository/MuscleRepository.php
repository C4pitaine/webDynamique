<?php

namespace App\Repository;

use App\Entity\Muscle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Muscle>
 *
 * @method Muscle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Muscle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Muscle[]    findAll()
 * @method Muscle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MuscleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Muscle::class);
    }

    /**
     * Permet de faire une recherche sur les noms des muscles
     *
     * @param string $search
     * @return array|null
     */
    public function search(string $search,?int $limit = null,?int $offset = 0): ?array
    {
        $search = htmlspecialchars($search);

        return $this->createQueryBuilder('m')
                    ->select('m as muscle','m.id,m.name,m.image,m.description')
                    ->where('m.name LIKE :search')
                    ->setParameter('search','%'.$search.'%')
                    ->setMaxResults($limit)
                    ->setFirstResult($offset)
                    ->getQuery()
                    ->getResult();
    }

    //    /**
    //     * @return Muscle[] Returns an array of Muscle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Muscle
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
