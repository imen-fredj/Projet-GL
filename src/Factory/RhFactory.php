<?php
namespace App\Factory;

use App\Entity\Rh;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RhFactory implements UserFactoryInterface {
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function createUser(string $email, string $password, array $data): Rh {
        $rh = new Rh();
        $rh->setEmail($email);
        
        // Hash the password (security best practice)
        $hashedPassword = $this->passwordHasher->hashPassword($rh, $password);
        $rh->setPassword($hashedPassword);
        
        $rh->setRoles(['ROLE_Rh']);
        $rh->setActive('False'); // Use boolean instead of string
        
        // Set user details from $data (or defaults)
        $rh->setNom($data['nom'] ?? '');
        $rh->setPrenom($data['prenom'] ?? 'Rh');
        $rh->setAdresse($data['adresse'] ?? '');
        $rh->setCin($data['cin'] ?? null);
        $rh->setTelephone($data['telephone'] ?? null);
        
        $rh->setImage($data['image'] ?? 'default_admin.png');
        
        return $rh;
    }
}