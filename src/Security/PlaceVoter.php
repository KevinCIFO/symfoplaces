<?php

namespace App\Security;

use App\Entity\Place;
use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class PlaceVoter extends Voter {
    private $security, $operaciones;

    public function __construct(Security $security) {
        $this->security = $security;

        $this->operaciones = ['create', 'edit', 'delete'];
    }

    protected function supports(String $attribute, $subject): bool {
        if(!in_array($attribute, $this->operaciones)) {
            return false;
        }

        if(!$subject instanceof Place) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(String $attribute, $lugar, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        $method = 'can' .ucfirst($attribute);

        return $this->$method($lugar, $user);
    }

    private function canCreate(Place $lugar, User $user): bool {
        return true;
    }

    private function canEdit(Place $lugar, User $user): bool {
        return $user === $lugar->getUser() || $this->security->isGranted('ROLE_EDITOR');
    }
    
    private function canDelete(Place $lugar, User $user): bool {
        return $this->canEdit($lugar, $user);
    }
}