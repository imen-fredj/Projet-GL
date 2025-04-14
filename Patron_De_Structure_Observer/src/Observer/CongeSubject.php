<?php

// src/Observer/CongeSubject.php
namespace App\Observer;

use App\Entity\Conge;

class CongeSubject implements SubjectInterface
{
    private array $subscribers = [];
    private Conge $conge;

    public function __construct(Conge $conge)
    {
        $this->conge = $conge;
    }

    public function attach(SubscriberInterface $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    public function detach(SubscriberInterface $subscriber): void
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
    public function demandeCreated(): void
    {
        $this->notify('conge.created', [
            'message' => 'Nouvelle demande de congé créée'
        ]);
    }

    public function demandeAccepted(): void
    {
        $this->notify('conge.accepted', [
            'message' => 'Demande de congé acceptée'
        ]);
    }

    public function demandeRejected(string $reason): void
    {
        $this->notify('conge.rejected', [
            'message' => 'Demande de congé rejetée',
            'reason' => $reason
        ]);
    }
}