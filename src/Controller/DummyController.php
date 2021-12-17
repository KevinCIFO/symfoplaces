<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

class DummyController extends AbstractController
{
    #[Route('/dummy', name: 'dummy')]
    public function index(Request $request): Response
    {
        return $request->cookies->has('autor') ?
            new Response("He recuperado: " .$request->cookies->get('autor')) :
            new Response("No existe la cookie con nombre 'autor'");
    }

    #[Route('/dummy/addrole/{id}', name: 'add_role')]
    public function addRole(User $usuario, EntityManagerInterface $em)
    {
        if($usuario) {
            $roles = $usuario->getRoles();
            array_push($roles, 'ROLE_SUPERVISOR');
            $usuario->setRoles($roles);
            $em->flush();

            return new Response('Rol añadido');
        }

        return new Response('No se pudo añadir el rol');
    }

    #[Route('/dummy/removerole/{id}', name: 'remove_role')]
    public function removeRole(User $usuario, EntityManagerInterface $em)
    {
        if($usuario) {
            $roles = $usuario->getRoles();
            $roles = array_diff($roles, ['ROLE_SUPERVISOR']);
            $usuario->setRoles($roles);
            $em->flush();

            return new Response('Rol eliminado');
        }

        return new Response('No se pudo quitar el rol');
    }
}
