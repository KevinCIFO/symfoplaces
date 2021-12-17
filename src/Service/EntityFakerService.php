<?php

namespace App\Service;

class EntityFakerService {
    public function getMock(String $className) {
        $fullName = '\\App\\Entity\\'.$className;
        return new $fullName();
    }
}