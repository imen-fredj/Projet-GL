<?php


namespace App\Observer;

interface SubscriberInterface
{
    public function update(string $event, array $data = []): void;
}