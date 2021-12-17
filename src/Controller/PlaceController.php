<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Place;
use App\Entity\Photo;
use App\Entity\Comment;
use App\Entity\User;

use App\Form\PlaceFormType;
use App\Form\PlaceAddPhotoFormType;
use App\Form\PlaceAddCommentFormType;
use App\Form\PlaceDeleteFormType;
use App\Form\SearchFormType;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Psr\Log\LoggerInterface;

use App\Service\FileService;
use App\Service\PaginatorService;
use App\Service\SimpleSearchService;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Filesystem\Filesystem;

class PlaceController extends AbstractController
{
    #[Route('/lugares/{pagina}', name: 'lugares', defaults: ['pagina' => 1])]
    public function index(int $pagina, PaginatorService $paginator): Response
    {
        $paginator->setEntityType('App\Entity\Place');
        $paginator->setLimit($this->getParameter('app.placelist_results'));

        $lugares = $paginator->findAllEntities($pagina);

        return $this->render('place/list.html.twig', ['lugares' => $lugares, 'paginator' => $paginator]);
    }

    #[Route('/lugar/{id<\d+>}', name: 'lugar_show')]
    public function show(Place $lugar): Response
    {
        $usuario = new User();

        $formularioAddComment = $this->createForm(PlaceAddCommentFormType::class, NULL, [
            'action' => $this->generateUrl('lugar_add_comment', ['id' => $lugar->getId()])
        ]);

        return $this->renderForm('place/show.html.twig', ['usuario' => $usuario, 'formularioAddComment' => $formularioAddComment,'lugar' => $lugar]);
    }

    #[Route('/lugar/search', name: 'lugar_search', methods: ['GET', 'POST'])]
    public function search(Request $request, SimpleSearchService $busqueda): Response
    {
        $formulario = $this->createForm(SearchFormType::class, $busqueda, [
            'field_choices' => [
                'Nombre' => 'nombre',
                'País' => 'pais',
                'Tipo' => 'tipo',
                'Valoración' => 'valoracion'
            ],
            'order_choices' => [
                'ID' => 'id',
                'Nombre' => 'nombre',
                'País' => 'pais',
                'Tipo' => 'tipo'
            ]
        ]);


        $formulario->get('campo')->setData($busqueda->campo);
        $formulario->get('orden')->setData($busqueda->orden);

        $formulario->handleRequest($request);

        $lugares = $busqueda->search('App\Entity\Place');

        return $this->renderForm('place/buscar.html.twig', [
            'formulario' => $formulario,
            'lugares' => $lugares
        ]);

    }

    #[Route('/lugar/create', name: 'lugar_create')]
    public function create(Request $request, LoggerInterface $appInfoLogger, EntityManagerInterface $entityManager): Response
    {
        $lugar = new Place();

        $this->denyAccessUnlessGranted('create', $lugar);

        $formulario = $this->createForm(PlaceFormType::class, $lugar);

        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $lugar->setUser($this->getUser());

            $entityManager->persist($lugar);
            $entityManager->flush();

            $mensaje = 'Lugar ' .$lugar->getNombre(). ' guardado con id ' .$lugar->getId();
            $this->addFlash('success', $mensaje);
            $appInfoLogger->info($mensaje);

            return $this->redirectToRoute('lugar_show', ['id' => $lugar->getId()]);
        }

        return $this->renderForm('place/create.html.twig', ['formulario' => $formulario]);
    }

    #[Route('/lugar/update/{id}', name: 'lugar_update')]
    public function update(Place $lugar, Request $request, LoggerInterface $appInfoLogger, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $lugar);

        $formulario = $this->createForm(PlaceFormType::class, $lugar);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $entityManager->flush();

            $mensaje = 'Lugar ' .$lugar->getNombre(). ' con id ' .$lugar->getId(). ' actualizado correctamente';
            $this->addFlash('success', $mensaje);
            $appInfoLogger->info($mensaje);

            return $this->redirectToRoute('lugar_show', ['id' => $lugar->getId()]);
        }

        $formularioAddPhoto = $this->createForm(PlaceAddPhotoFormType::class, NULL, [
            'action' => $this->generateUrl('lugar_add_photo', ['id' => $lugar->getId()])
        ]);

        return $this->renderForm('place/edit.html.twig', [
            'formulario' => $formulario,
            'formularioAddPhoto' => $formularioAddPhoto, 
            'lugar' => $lugar
        ]);
    }

    #[Route('/lugar/updatePhoto/{id}', name: 'photo_update')]
    public function updatePhoto(Photo $foto, Request $request, LoggerInterface $appInfoLogger, FileService $uploader, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $foto);

        $fichero = $foto->getRuta();
        
        $formulario = $this->createForm(PlaceAddPhotoFormType::class, $foto);
        
        $formulario->handleRequest($request);
      
        if($formulario->isSubmitted() && $formulario->isValid()){
            $file = $formulario->get('ruta')->getData();

            if($file){
                $uploader->targetDirectory = $this->getParameter('app.covers_root');
                $fichero = $uploader->replace($file, $fichero);
            }
            
            $foto->setRuta($fichero);

            $entityManager->flush();

            $mensaje = 'Foto ' .$foto->getTitulo(). ' con id ' .$foto->getId(). ' actualizada correctamente';
            $this->addFlash('success', $mensaje);
            $appInfoLogger->info($mensaje);

            return $this->redirectToRoute('lugar_update', ['id' => $foto->getPlace()->getId()]);
        }

        return $this->renderForm('photo/edit.html.twig', [
            'formulario' => $formulario, 
            'foto' => $foto
        ]);
    }

    #[Route('/lugar/delete/{id}', name: 'lugar_delete')]
    public function delete(Place $lugar, Request $request, LoggerInterface $appInfoLogger, FileService $uploader, EntityManagerInterface $entityManager): Response
    {
        $formulario = $this->createForm(PlaceDeleteFormType::class, $lugar);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $entityManager->getRepository(Photo::class);
            $uploader->targetDirectory = $this->getParameter('app.covers_root');
            $fotos = $lugar->getPhotos();
            foreach($fotos as $foto) {
                $uploader->delete($foto->getRuta());
                $entityManager->remove($foto);
            }

            $entityManager->getRepository(Comment::class);
            $comentarios = $lugar->getComments();
            foreach($comentarios as $comentario) {
                $entityManager->remove($comentario);
            }

            $entityManager->getRepository(Place::class);
            $entityManager->remove($lugar);
            $entityManager->flush();

            $mensaje = 'Lugar ' .$lugar->getNombre(). ' eliminado correctamente';
            $this->addFlash('success', $mensaje);
            $appInfoLogger->info($mensaje);

            return $this->redirectToRoute('lugares');
        }

        return $this->renderForm('place/delete.html.twig', [
            'formulario' => $formulario, 
            'lugar' => $lugar
        ]);
    }

    #[Route('/lugar/addphoto/{id<\d+>}', name: 'lugar_add_photo', methods: ['POST'])]
    public function addPhoto(Place $lugar, Request $request, LoggerInterface $appInfoLogger, FileService $uploader, EntityManagerInterface $em)
    {
        $foto = new Photo();

        $formularioAddPhoto = $this->createForm(PlaceAddPhotoFormType::class);
        $formularioAddPhoto->handleRequest($request);

        if($formularioAddPhoto->isSubmitted() && $formularioAddPhoto->isValid()){
            $file = $formularioAddPhoto->get('ruta')->getData();

            if($file){
                $uploader->targetDirectory = $this->getParameter('app.covers_root');
                $foto->setRuta($uploader->upload($file));
            }
            $descripcionData = $formularioAddPhoto->get('descripcion')->getData();
            $tituloData = $formularioAddPhoto->get('titulo')->getData();
            $fechaFotoData = $formularioAddPhoto->get('fecha')->getData();

            $foto->setDescripcion($descripcionData);
            $foto->setTitulo($tituloData);
            $foto->setFecha($fechaFotoData);
            
            $lugar->addPhoto($foto);
            
            $em->persist($foto);
            $em->flush();

            $mensaje = 'Foto del lugar ' .$lugar->getNombre(). ' con id ' .$lugar->getId(). ' añadida correctamente';
            $this->addFlash('success', $mensaje);
            $appInfoLogger->info($mensaje);

        }

        return $this->redirectToRoute('lugar_update', ['id' => $lugar->getId()]);
    }

    #[Route('/lugar/removephoto/{id<\d+>}', name: 'lugar_remove_photo', methods: ['GET'])]
    public function removePhoto(Photo $foto, Request $request, LoggerInterface $appInfoLogger, FileService $uploader, EntityManagerInterface $em)
    {
        if($foto->getRuta()) {
            $filesystem = new Filesystem();
            $directorio = $this->getParameter('app.covers_root');
            $filesystem->remove($directorio.'/'.$foto->getRuta());
        }
            $em->remove($foto);
            $em->flush();

            $mensaje = 'Foto ' .$foto->getTitulo(). ' con id ' .$foto->getId(). ' eliminada correctamente';
            $this->addFlash('success', $mensaje);
            $appInfoLogger->info($mensaje);
        

        return $this->redirectToRoute('lugar_update', ['id' => $foto->getPlace()->getId()]);
    }

    #[Route('/lugar/addcomment/{id<\d+>}', name: 'lugar_add_comment', methods: ['POST'])]
    public function addComment(Place $lugar, Request $request, LoggerInterface $appUserInfoLogger, EntityManagerInterface $em)
    {
        $comentario = new Comment();

        $this->denyAccessUnlessGranted('create', $comentario);

        $formularioAddComment = $this->createForm(PlaceAddCommentFormType::class);
        $formularioAddComment->handleRequest($request);

        if($formularioAddComment->isSubmitted() && $formularioAddComment->isValid()){
            $comentario->setUser($this->getUser());

            $comentarioData = $formularioAddComment->get('texto')->getData();

            $comentario->setTexto($comentarioData);
            
            $lugar->addComment($comentario);
            
            $em->persist($comentario);
            $em->flush();

            $mensaje = 'Comentario del lugar ' .$lugar->getNombre(). ' con id ' .$lugar->getId(). ' añadido correctamente';
            $this->addFlash('success', $mensaje);
            $appUserInfoLogger->info($mensaje);

        }

        return $this->redirectToRoute('lugar_show', ['id' => $lugar->getId()]);
    }

    #[Route('/lugar/removecomment/{id<\d+>}', name: 'lugar_remove_comment')]
    public function removeComment(Comment $comentario, Request $request, LoggerInterface $appInfoLogger, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $comentario);

        $entityManager->remove($comentario);
        $entityManager->flush();

        $mensaje = 'Comentario eliminado correctamente';
        $this->addFlash('success', $mensaje);
        $appInfoLogger->info($mensaje);

        return $this->redirectToRoute('lugar_show', ['id' => $comentario->getPlace()->getId()]);
    }
}
