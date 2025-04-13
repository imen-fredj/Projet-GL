<?php

namespace App\Entity;

use App\Repository\ModificationInformationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModificationInformationRepository::class)]
class ModificationInformation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedemande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'ModificationInformation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeModification $TypeModification = null;

    #[ORM\ManyToOne(inversedBy: 'conges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rh $Rh = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDatedemande(): ?\DateTimeInterface
    {
        return $this->datedemande;
    }

    public function setDatedemande(\DateTimeInterface $datedemande): self
    {
        $this->datedemande = $datedemande;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    public function getTypeModification(): ?TypeModification
    {
        return $this->TypeModification;
    }

    public function setTypeModification(?TypeModification $TypeModification): self
    {
        $this->TypeModification = $TypeModification;

        return $this;
    }
    public function getRh(): ?Rh
    {
        return $this->Rh;
    }

    public function setRh(?Rh $Rh): self
    {
        $this->Rh = $Rh;

        return $this;
    }
}
