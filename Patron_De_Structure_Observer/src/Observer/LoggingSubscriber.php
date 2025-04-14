<?php

// src/Observer/LoggingSubscriber.php
namespace App\Observer;

class LoggingSubscriber implements SubscriberInterface
{
    public function update(string $event, array $data = []): void
    {
        $conge = $data['conge'];
        $message = $data['message'] ?? '';

        $logMessage = sprintf(
            "[%s] %s - Congé ID: %d, Employé: %s %s",
            date('Y-m-d H:i:s'),
            $event,
            $conge->getId(),
            $conge->getRh()->getPrenom(),
            $conge->getRh()->getNom()
        );

        // Dans une vraie application, vous utiliseriez un logger
        echo $logMessage . PHP_EOL;
    }
}