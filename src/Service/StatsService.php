<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService{

    /**
     * Le manager de Doctrine qui nous permet notament de trouver le repository 
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function bestEval(): ?array
    {
        return $this->manager->createQuery(
            'SELECT e, u
            FROM App\Entity\Evaluation e
            JOIN e.user u
            WHERE e.note >= 4
            ORDER BY e.id DESC'
        )
        ->setMaxResults(3)
        ->getResult();
        
    }
}