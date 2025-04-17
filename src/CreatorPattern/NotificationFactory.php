<?php
namespace App\Factory;

use App\Entity\Notification;
use App\Entity\Rh;
use App\Entity\Conge;

class NotificationFactory
{
    public function create(
        string $text,
        Rh $recepteur,
        Rh $destinateur,
        Conge $conge
    ): Notification {
        $notification = new Notification();
        $notification->setText($text);
        $notification->setRecepteur($recepteur);
        $notification->setDestinateur($destinateur);
        $notification->setConge($conge);
        $notification->setDateNotification(new \DateTimeImmutable());
        
        return $notification;
    }
}
