<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $email = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Expediteur $expediteur = null;

    #[ORM\ManyToOne(cascade: ['persist'],inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Destinataire $destinataire = null;

    #[ORM\ManyToMany(targetEntity: Marchandise::class, inversedBy: 'devis', cascade: ['persist'])]
    private Collection $marchandise;

    #[ORM\Column]
    private ?bool $clientType = null;

    #[ORM\Column]
    private ?bool $serviceType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $societe = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    public function __construct()
    {
        $this->marchandise = new ArrayCollection();
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    /**
     * @return Collection<int, Marchandise>
     */
    public function getMarchandise(): Collection
    {
        return $this->marchandise;
    }

    public function addMarchandise(Marchandise $marchandise): self
    {
        if (!$this->marchandise->contains($marchandise)) {
            $this->marchandise->add($marchandise);
        }

        return $this;
    }

    public function removeMarchandise(Marchandise $marchandise): self
    {
        $this->marchandise->removeElement($marchandise);

        return $this;
    }

    public function isClientType(): ?bool
    {
        return $this->clientType;
    }

    public function setClientType(bool $clientType): self
    {
        $this->clientType = $clientType;

        return $this;
    }

    public function isServiceType(): ?bool
    {
        return $this->serviceType;
    }

    public function setServiceType(bool $serviceType): self
    {
        $this->serviceType = $serviceType;

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

}
