<?php

// src/Observer/CongeSubject.php
namespace App\Observer;

use App\Entity\Conge;

class CongeSubject implements SubjectInterface
{
    private array $subscribers = [];
    private ?Conge $conge = null;

    private string $status = 'pending';

    public function __construct()
    {
    }

    public function attach(ObserverInterface $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    public function detach(ObserverInterface $subscriber): void
    {
        $key = array_search($subscriber, $this->subscribers, true);
        if ($key !== false) {
            unset($this->subscribers[$key]);
        }
    }

    public function notify(string $event, array $data = []): void
    {
        foreach ($this->subscribers as $subscriber) {
            $subscriber->update($event, array_merge(['conge' => $this->conge], $data));
        }
    }

    // Méthodes spécifiques pour déclencher des événements
    public function demandeCreated(Conge $conge): void
    {
        $this->conge = $conge;
        $this->status = 'pending';
        $this->notify('conge.created', [
            'message' => 'Nouvelle demande de congé créée'
        ]);
    }

    public function demandeAccepted(array $context = []): void
    {
        if (!$this->conge) {
            throw new \LogicException('Aucune demande en cours');
        }

        $this->status = 'accepted';
        $this->notify('conge.accepted', [
            'message' => 'Demande de congé acceptée',
            'context' => $context
        ]);
    }

    public function demandeRejected(string $reason): void
    {
        if (!$this->conge) {
            throw new \LogicException('Aucune demande en cours');
        }

        $this->status = 'rejected';
        $this->notify('conge.rejected', [
            'message' => 'Demande de congé rejetée',
            'reason' => $reason
        ]);
    }
}