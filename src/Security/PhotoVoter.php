<?php

namespace App\Security;

use App\Entity\Photo;
use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class PhotoVoter extends Voter {
    private $security, $operaciones;

    public function __construct(Security $security) {
        $this->security = $security;

        $this->operaciones = ['edit', 'delete'];
    }

    protected function supports(String $attribute, $subject): bool {
        if(!in_array($attribute, $this->operaciones)) {
            return false;
        }

        if(!$subject instanceof Photo) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(String $attribute, $foto, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        $method = 'can' .ucfirst($attribute);

        return $this->$method($foto, $user);
    }

    private function canEdit(Photo $foto, User $user): bool {
        return $user === $foto->getPlace()->getUser() || $this->security->isGranted('ROLE_EDITOR');
    }
    
    private function canDelete(Photo $foto, User $user): bool {
        return $this->canEdit($foto, $user);
    }
}