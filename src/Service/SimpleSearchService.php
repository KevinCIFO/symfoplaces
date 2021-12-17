<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class SimpleSearchService {
    public $campo = 'id', $valor='%', $orden = 'id', $sentido = 'ASC', $limite = 5;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function search(String $entityType):array {
        $consulta = $this->entityManager->createQuery (
            "SELECT p
             FROM $entityType p
             WHERE p.$this->campo LIKE :valor
             ORDER BY p.$this->orden $this->sentido"
        )

        ->setParameter('valor', '%' .$this->valor. '%')
        ->setMaxResults($this->limite);

        return $consulta->getResult();
    }
}