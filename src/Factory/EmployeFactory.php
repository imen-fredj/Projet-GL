<?php
namespace App\Factory;

use App\Entity\Rh;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeFactory implements UserFactoryInterface {
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    
    public function createUser(string $email, string $password, array $data): Rh {
        $employe = new Rh();
        $employe->setEmail($email);
        
        // Hash the password (security best practice)
        $hashedPassword = $this->passwordHasher->hashPassword($employe, $password);
        $user->setPassword($hashedPassword);
        
        $employe->setRoles(['ROLE_USERS']);
        $employe->setActive('False'); // Use boolean instead of string
        
        // Set user details from $data (or defaults)
        $employe->setNom($data['nom'] ?? '');
        $employe->setPrenom($data['prenom'] ?? 'employe');
        $employe->setAdresse($data['adresse'] ?? '');
        $employe->setCin($data['cin'] ?? null);
        $employe->setTelephone($data['telephone'] ?? null);
        
        $employe->setImage($data['image'] );
        
        return $employe;
    }
}