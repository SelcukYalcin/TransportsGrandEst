<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $expediteur = null;

    #[ORM\Column(length: 255)]
    private ?string $destinataire = null;

    #[ORM\ManyToOne(inversedBy: 'livraisons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $membre = null;

    #[ORM\OneToMany(mappedBy: 'livraison', targetEntity: Marchandise::class)]
    private Collection $marchandises;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnlevement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateLivree = null;

    #[ORM\Column(length: 255)]
    private ?string $serviceLivraison = null;

    #[ORM\OneToMany(mappedBy: 'livraison', targetEntity: Marchandise::class)]
    private Collection $marchandise;

    public function __construct()
    {
        $this->marchandises = new ArrayCollection();
        $this->marchandise = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpediteur(): ?string
    {
        return $this->expediteur;
    }

    public function setExpediteur(string $expediteur): self
    {
        $this->expediteur = $expediteur;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): self
    {
        $this->destinataire = $destinataire;

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

    /**
     * @return Collection<int, Marchandise>
     */
    public function getMarchandises(): Collection
    {
        return $this->marchandises;
    }

    public function addMarchandise(Marchandise $marchandise): self
    {
        if (!$this->marchandises->contains($marchandise)) {
            $this->marchandises->add($marchandise);
            $marchandise->setLivraison($this);
        }

        return $this;
    }

    public function removeMarchandise(Marchandise $marchandise): self
    {
        if ($this->marchandises->removeElement($marchandise)) {
            // set the owning side to null (unless already changed)
            if ($marchandise->getLivraison() === $this) {
                $marchandise->setLivraison(null);
            }
        }

        return $this;
    }

    public function getDateEnlevement(): ?\DateTimeInterface
    {
        return $this->dateEnlevement;
    }

    public function setDateEnlevement(\DateTimeInterface $dateEnlevement): self
    {
        $this->dateEnlevement = $dateEnlevement;

        return $this;
    }

    public function getDateLivree(): ?\DateTimeInterface
    {
        return $this->dateLivree;
    }

    public function setDateLivree(\DateTimeInterface $dateLivree): self
    {
        $this->dateLivree = $dateLivree;

        return $this;
    }

    public function getServiceLivraison(): ?string
    {
        return $this->serviceLivraison;
    }

    public function setServiceLivraison(string $serviceLivraison): self
    {
        $this->serviceLivraison = $serviceLivraison;

        return $this;
    }

    /**
     * @return Collection<int, Marchandise>
     */
    public function getMarchandise(): Collection
    {
        return $this->marchandise;
    }

}
