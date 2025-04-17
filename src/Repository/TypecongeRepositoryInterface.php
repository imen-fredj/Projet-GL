<?php
namespace App\Repository;

use App\Entity\Typeconge;

interface TypecongeRepositoryInterface
{
    public function findAll();
    public function find($id, $lockMode = null, $lockVersion = null);
    public function save(Typeconge $entity, bool $flush = false): void;
    public function remove(Typeconge $entity, bool $flush = false): void;
    public function countRelatedConges(Typeconge $typeconge): int;
}