<?php

namespace App\Entity;

use App\Repository\CongeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CongeRepository::class)]
class Conge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column]
    private ?int $nbjour = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'conges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Typeconge $Typeconge = null;


  

    #[ORM\ManyToOne(inversedBy: 'conges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rh $Rh = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedemande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\OneToMany(mappedBy: 'conge', targetEntity: Notification::class)]
    private Collection $notification;

    public function __construct()
    {
        $this->notification = new ArrayCollection();
    }

   

  

    public function getId(): ?int
    {
        return $this->id;
    }

  

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getNbjour(): ?int
    {
        return $this->nbjour;
    }

    public function setNbjour(int $nbjour): self
    {
        $this->nbjour = $nbjour;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeconge(): ?Typeconge
    {
        return $this->Typeconge;
    }

    public function setTypeconge(?Typeconge $Typeconge): self
    {
        $this->Typeconge = $Typeconge;

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

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotification(): Collection
    {
        return $this->notification;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notification->contains($notification)) {
            $this->notification->add($notification);
            $notification->setConge($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notification->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getConge() === $this) {
                $notification->setConge(null);
            }
        }

        return $this;
    }

    ///////////////// OCL /////////////////////////////

    public function isValid(): bool
{
    if (!$this->datedebut || !$this->datefin || !$this->nbjour) {
        return false;
    }

    $diff = $this->datedebut->diff($this->datefin)->days + 1;

    return $this->datedebut < $this->datefin && $this->nbjour <= 30 && $this->nbjour == $diff;
}



}
