<?php
// UserFactoryInterface.php
namespace App\Factory;

interface UserFactoryInterface {
    public function createUser(string $email, string $password, array $data); // Return type should be your user entity
}