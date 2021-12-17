<?php

namespace App\Security;

use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter {
    private $security, $operaciones;

    public function __construct(Security $security) {
        $this->security = $security;

        $this->operaciones = ['read', 'edit', 'delete'];
    }

    protected function supports(String $attribute, $subject): bool {
        if(!in_array($attribute, $this->operaciones)) {
            return false;
        }

        if(!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(String $attribute, $userCheck, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        $method = 'can' .ucfirst($attribute);

        return $this->$method($userCheck, $user);
    }

    private function canRead(User $user): bool {
        return $this->security->isGranted('ROLE_SUPERVISOR') || $this->security->isGranted('ROLE_ADMIN');
    }

    private function canEdit(User $user): bool {
        return $user === $this->security->getUser() || $this->security->isGranted('ROLE_ADMIN');
    }
    
    private function canDelete(User $user): bool {
        return $this->canEdit($user);
    }
}