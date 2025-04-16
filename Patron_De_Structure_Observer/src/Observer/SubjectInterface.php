<?php

namespace App\Observer;

interface SubjectInterface
{
    public function attach(ObserverInterface $subscriber): void;
    public function detach(ObserverInterface $subscriber): void;
    public function notify(string $event, array $data = []): void;
}