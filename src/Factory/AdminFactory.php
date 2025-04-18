<?php
namespace App\Factory;

use App\Entity\Rh;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFactory implements UserFactoryInterface {
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function createUser(string $email, string $password, array $data): Rh {
        $admin = new Rh();
        $admin->setEmail($email);
        
        // Hash the password (security best practice)
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);
        
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setActive('True'); // Use boolean instead of string
        
        // Set user details from $data (or defaults)
        $admin->setNom($data['nom'] ?? '');
        $admin->setPrenom($data['prenom'] ?? 'Admin');
        $admin->setAdresse($data['adresse'] ?? '');
        $admin->setCin($data['cin'] ?? null);
        $admin->setTelephone($data['telephone'] ?? null);
        
        $admin->setImage($data['image'] ?? 'default_admin.png');
        
        return $admin;
    }
}