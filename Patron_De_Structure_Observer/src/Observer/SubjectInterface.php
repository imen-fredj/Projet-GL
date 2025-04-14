<?php

namespace App\Observer;

interface SubjectInterface
{
    public function attach(SubscriberInterface $subscriber): void;
    public function detach(SubscriberInterface $subscriber): void;
    public function notify(string $event, array $data = []): void;
}