<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\ShareMovieMailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function index(MailerInterface $mailer): Response
    {
        // Création d'un objet de type Symfony\Component\Mime\Email
        $email = (new Email())
            ->from('formation@plb.fr')
            ->to('donaldtrump@usa.us')

            /*
            ->cc()
            ->bcc()
            ->replyTo()
            ->priority()
            */


            ->subject('Test d email')
            ->text('Envoi d\'un mail via MailerInterface')
            ->html('<h1>Test mail</h1>');

        // Envoi du mail via MailerInterface
        $mailer->send($email);

        return $this->render('mail/index.html.twig', [
        ]);
    }


    /**
     * @Route(
     *      "/shareMovie/{id}",
     *      name="Share Movie"
     * )
     */
    public function shareMovie(Movie $movie, Request $request, MailerInterface $mailer){
        // Param Converter nous donne le movie dont l'id est passé en paramètre
        //$movie

        // Données du formulaire
        $formulaireMail = $this->createForm(
            ShareMovieMailType::class
        );
        // Prise cn charge de la requête
        $formulaireMail->handleRequest($request);

        if ($formulaireMail->isSubmitted() && $formulaireMail->isValid())
        {
            // Données du formulaire
            $data = $formulaireMail->getData();

            // On a reçu un tableau associatif
            $dest = $data['dest'];
            $object = $data['object'];
            $message = $data['message'];

            // Création du mail

            // Vue HTML du mail
            $html = $this->renderView(
                'mail/shareMovie.html.twig',
                [
                    'object' => $object,
                    'message' => $message,
                    'movie' => $movie
                ]
            );

            // Création d'un objet de type Symfony\Component\Mime\Email
            $email = (new Email())
                ->from('formation@plb.fr')
                ->to($dest)
                ->subject($object)
                ->text($message)
                ->html(
                    // Code HTML issu d'un template TWIG
                    $html
                );

            // Envoi du mail via MailerInterface
            $mailer->send($email);

            return $this->redirectToRoute(
                'movie_getMovie',
                [
                    'idMovie' => $movie->getId()
                ]
            );
        }


    }
}
