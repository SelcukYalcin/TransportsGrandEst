<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $dateVal = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?User $membre = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVal(): ?\DateTimeInterface
    {
        return $this->dateVal;
    }

    public function setDateVal(?\DateTimeInterface $dateVal): self
    {
        $this->dateVal = $dateVal;

        return $this;
    }

    public function getMembre(): ?User
    {
        return $this->membre;
    }

    public function setMembre(?User $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

}
