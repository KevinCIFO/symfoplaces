<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Form\UserFormType;
use App\Form\UserDeleteFormType;

use Psr\Log\LoggerInterface;

use App\Service\FileService;
use App\Service\PaginatorService;

use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('/user/pic/{imagen}', name: 'pic_show', methods: ['GET'])]
    public function showPic(String $imagen)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $ruta = $this->getParameter('app.users_pics_root');

        $response = new BinaryFileResponse($ruta.'/'.$imagen);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $imagen,
            iconv('UTF-8', 'ASCII//TRANSLIT', $imagen)
        );

        return $response;
    }

    #[Route('/usuarios/{pagina}', name: 'usuarios', defaults: ['pagina' => 1])]
    public function userlist(int $pagina, PaginatorService $paginator): Response
    {
        $user = new User();
        $this->denyAccessUnlessGranted('read', $user);

        $paginator->setEntityType('App\Entity\User');
        $paginator->setLimit($this->getParameter('app.placelist_results'));

        $usuarios = $paginator->findAllEntities($pagina);
        
        return $this->render('user/list.html.twig', ['usuarios' => $usuarios, 'paginator' => $paginator]);
    }

    #[Route('/showUser/{id}', name: 'user_show')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['usuario' => $user]);
    }

    #[Route('/editUser/{id}', name: 'user_edit')]
    public function update(User $user, Request $request, LoggerInterface $appUserInfoLogger, FileService $uploader, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $user);

        $fichero = $user->getImagen();

        $formulario = $this->createForm(UserFormType::class, $user);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $file = $formulario->get('imagen')->getData();

            if($file){
                $uploader->targetDirectory = $this->getParameter('app.users_pics_root');
                $fichero = $uploader->replace($file, $fichero);
            }
            
            $user->setImagen($fichero);

            $entityManager->flush();

            $mensaje = 'Datos del usuario ' .$user->getNombre(). ' actualizados correctamente';
            $this->addFlash('success', $mensaje);
            $appUserInfoLogger->info($mensaje);

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->renderForm('user/edit.html.twig', [
            'formulario' => $formulario,
            'usuario' => $user
        ]);
    }

    #[Route('/deleteUser/{id}', name: 'user_delete')]
    public function delete(User $user, Request $request, LoggerInterface $appUserInfoLogger, FileService $uploader, EntityManagerInterface $entityManager): Response
    {
        $formulario = $this->createForm(UserDeleteFormType::class, $user);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            if($user->getImagen()){
                $uploader->delete($user->getImagen());
            }

            $entityManager->remove($user);
            $entityManager->flush();

            $mensaje = 'Usuario ' .$user->getNombre(). ' eliminado correctamente';
            $this->addFlash('success', $mensaje);
            $appUserInfoLogger->info($mensaje);

            return $this->redirectToRoute('usuarios');
        }

        return $this->render('user/delete.html.twig', [
            'formulario' => $formulario->createView(), 
            'usuario' => $user
        ]);
    }
}