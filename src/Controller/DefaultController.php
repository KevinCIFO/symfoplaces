<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Place;

use App\Kernel;

use App\Form\ContactoFormType;

use App\Service\PaginatorService;

class DefaultController extends AbstractController
{
    #[Route('/{pagina<\d+>}', name: 'portada', defaults: ['pagina' => 1])]
    public function index(int $pagina, PaginatorService $paginator): Response
    {
        $paginator->setEntityType('App\Entity\Place');

        $lugares = $paginator->findAllEntities($pagina);

        return $this->render('portada.html.twig', ['lugares' => $lugares, 'paginator' => $paginator]);
    }

    #[Route('/contacto', name: 'contacto')]
    public function contacto(Request $request, MailerInterface $mailer): Response
    {
        $formulario = $this->createForm(ContactoFormType::class);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $nombre = $formulario->getData()['nombre'];
            $asunto = $formulario->getData()['asunto'];
            $mensaje = $formulario->getData()['mensaje'];
            $de = $formulario->getData()['email'];

            $email = (new TemplatedEmail())
                ->from(new Address($de, $nombre))
                ->to($this->getParameter('app.admin_email'))
                ->subject($asunto)
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'de' => $de,
                    'nombre' => $nombre,
                    'asunto' => $asunto,
                    'mensaje' => $mensaje
                ]);
            
            $mailer->send($email);

            $this->addFlash('success', 'Mensaje enviado correctamente');

            return $this->redirectToRoute('portada');
        }

        return $this->render('contacto.html.twig', ['formulario'=>$formulario->createView()]);
    }
}
