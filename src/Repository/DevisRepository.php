<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devis>
 *
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    public function save(Devis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Devis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function findByDate(\DateTime $date)
    // {
    //     $qb = $this->createQueryBuilder('d');
    //     $qb->where('d.dateVal = :dateVal')
    //     ->setParameter('dateVal', $date)
    //     ->orderBy('d.dateVal', 'DESC');

    //     return $qb->getQuery()->getResult();
    // }

    public function RechercheDevis($recherche)
    {
        $query = $this->createQueryBuilder('d')
            ->select('de', 'd')
            // ->join('d.marchandise', 'm')
            // ->join('d.expediteur', 'e')
            // ->join('d.membre', 'u')
            ->join('d.destinataire', 'de');

        if(!empty($recherche->label)){
            $query = $query
                ->andWhere('d.nom LIKE :recherche OR d.prenom LIKE :recherche OR de.nom LIKE :recherche') //OR d.prenom LIKE :recherche OR dest.nom LIKE :recherche
                ->setParameter('recherche', "%{$recherche->label}%");      
        }

        return $query->getQuery()->getResult();
        

    }
}