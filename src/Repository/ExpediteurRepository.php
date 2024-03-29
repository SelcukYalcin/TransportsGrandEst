<?php

namespace App\Repository;

use App\Entity\Devis;
use App\Entity\Expediteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExpediteurRepository extends ServiceEntityRepository

{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expediteur::class);
    }
    public function save(Expediteur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}