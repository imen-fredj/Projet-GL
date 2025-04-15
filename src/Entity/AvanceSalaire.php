<?php

namespace App\Entity;

use App\Repository\AvanceSalaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvanceSalaireRepository::class)]
class AvanceSalaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedemande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_avance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;
    #[ORM\ManyToOne(inversedBy: 'AvanceSalaire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rh $Rh = null;


    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

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

    public function getDateAvance(): ?\DateTimeInterface
    {
        return $this->date_avance;
    }

    public function setDateAvance(\DateTimeInterface $date_avance): self
    {
        $this->date_avance = $date_avance;

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
