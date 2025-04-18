<?php
namespace App\Factory;


use App\Entity\Rh;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory {
    private UserPasswordHasherInterface $passwordHasher;
    private AdminFactory $adminFactory;
    private RhFactory $rhFactory;
    private EmployeFactory $employeFactory;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        AdminFactory $adminFactory,
        RhFactory $rhFactory,
        EmployeFactory $employeFactory
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->adminFactory = $adminFactory;
        $this->rhFactory = $rhFactory;
        $this->employeFactory = $employeFactory;
    }

    public function create(string $type, string $email, string $plainPassword, array $data): Rh {
        $factory = match($type) {
            'admin' => $this->adminFactory,
            'rh'    => $this->rhFactory,
            default => $this->employeFactory,
        };

        return $factory->createUser($email, $plainPassword, $data);
    }
    public function updateUser(Rh $user, array $data): Rh
{
    if (!empty($data['email'])) {
        $user->setEmail($data['email']);
    }

    if (!empty($data['plainPassword'])) {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['plainPassword']);
        $user->setPassword($hashedPassword);
    }

    // Mise à jour des autres champs
    $user->setNom($data['nom'] ?? $user->getNom());
    $user->setPrenom($data['prenom'] ?? $user->getPrenom());
    $user->setAdresse($data['adresse'] ?? $user->getAdresse());
    $user->setCin($data['cin'] ?? $user->getCin());
    $user->setTelephone($data['telephone'] ?? $user->getTelephone());
    $user->setImage($data['image'] ?? $user->getImage());
    $user->setActive($data['active'] ?? $user->getActive());

    return $user;
}
}