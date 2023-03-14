<?php

namespace App\Repository;

use App\Entity\Expediteur;
use App\Entity\Marchandise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MarchandiseRepository extends ServiceEntityRepository

{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marchandise::class);
    }


}