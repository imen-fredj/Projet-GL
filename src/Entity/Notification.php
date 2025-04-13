<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;


    #[ORM\Column]
    private ?int $is_read=0 ;

    #[ORM\ManyToOne(targetEntity: Conge::class)]
    private ?Conge $conge = null;
    
    #[ORM\ManyToOne(targetEntity: Rh::class)]
    #[ORM\JoinColumn(name: 'destinateur_id', nullable: true)]
    private ?Rh $destinateur = null; 

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?Rh $recepteur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_notification = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    

    public function getIsRead(): ?int
    {
        return $this->is_read;
    }

    public function setIsRead(int $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getRecepteur(): ?Rh
    {
        return $this->recepteur;
    }

    public function setRecepteur(?Rh $recepteur): self
    {
        $this->recepteur = $recepteur;

        return $this;
    }

    public function getDateNotification(): ?\DateTimeInterface
    {
        return $this->date_notification;  // Match the property name
    }

    public function setDateNotification(\DateTimeInterface $date_notification): self
    {
        $this->date_notification = $date_notification;

        return $this;
    }

    public function getConge(): ?Conge
    {
        return $this->conge;
    }
    
    public function setConge(?Conge $conge): self
    {
        $this->conge = $conge;
    
        return $this;
    }
    public function getDestinateur(): ?Rh
    {
        return $this->destinateur;
    }

    public function setDestinateur(?Rh $destinateur): self
    {
        $this->destinateur = $destinateur;
        return $this;
    }
}
