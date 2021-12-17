<?php

namespace App\Security;

use App\Entity\Comment;
use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class CommentVoter extends Voter {
    private $security, $operaciones;

    public function __construct(Security $security) {
        $this->security = $security;

        $this->operaciones = ['create', 'delete'];
    }

    protected function supports(String $attribute, $subject): bool {
        if(!in_array($attribute, $this->operaciones)) {
            return false;
        }

        if(!$subject instanceof Comment) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(String $attribute, $comentario, TokenInterface $token): bool {
        $user = $token->getUser();

        if(!$user instanceof User) {
            return false;
        }

        $method = 'can' .ucfirst($attribute);

        return $this->$method($comentario, $user);
    }

    private function canCreate(Comment $comentario, User $user): bool {
        return $user === $this->security->getUser();
    }
    
    private function canDelete(Comment $comentario, User $user): bool {
        return $user === $comentario->getUser() || $user === $comentario->getPlace()->getUser() || $this->security->isGranted('ROLE_EDITOR');
    }
}