<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MarchandiseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarchandiseRepository::class)]
#[ApiResource]
class Marchandise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $conditionnement = null;

    #[ORM\Column(length: 255)]
    private ?string $typeMarchandise = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\Column]
    private ?float $longueur = null;

    #[ORM\Column]
    private ?float $largeur = null;

    #[ORM\Column]
    private ?float $hauteur = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\ManyToOne(inversedBy: 'marchandises')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Expediteur $expediteur = null;

    #[ORM\ManyToOne(inversedBy: 'marchandises')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Destinataire $destinataire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConditionnement(): ?string
    {
        return $this->conditionnement;
    }

    public function setConditionnement(string $conditionnement): self
    {
        $this->conditionnement = $conditionnement;

        return $this;
    }

    public function getTypeMarchandise(): ?string
    {
        return $this->typeMarchandise;
    }

    public function setTypeMarchandise(string $typeMarchandise): self
    {
        $this->typeMarchandise = $typeMarchandise;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->longueur;
    }

    public function setLongueur(float $longueur): self
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(float $largeur): self
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getHauteur(): ?float
    {
        return $this->hauteur;
    }

    public function setHauteur(float $hauteur): self
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getExpediteur(): ?Expediteur
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Expediteur $expediteur): self
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDestinataire(): ?Destinataire
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Destinataire $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }
}
