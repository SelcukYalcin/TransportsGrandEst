<?php

namespace App\Entity;

use App\Repository\DestinataireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DestinataireRepository::class)]
class Destinataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\OneToMany(mappedBy: 'destinataire', targetEntity: Marchandise::class)]
    private Collection $marchandises;

    #[ORM\OneToMany(mappedBy: 'destinataire', targetEntity: Devis::class)]
    private Collection $devis;

    public function __construct()
    {
        $this->marchandises = new ArrayCollection();
        $this->devis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Marchandise>
     */
    public function getMarchandise(): Collection
    {
        return $this->marchandises;
    }

    public function addMarchandise(Marchandise $marchandise): self
    {
        if (!$this->marchandises->contains($marchandise)) {
            $this->marchandises->add($marchandise);
            $marchandise->setDestinataire($this);
        }

        return $this;
    }

    public function removeMarchandise(Marchandise $marchandise): self
    {
        if ($this->marchandises->removeElement($marchandise)) {
            // set the owning side to null (unless already changed)
            if ($marchandise->getDestinataire() === $this) {
                $marchandise->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevis(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis->add($devi);
            $devi->setDestinataire($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getDestinataire() === $this) {
                $devi->setDestinataire(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return  $this->nom.
                $this->ville;
    }
}
