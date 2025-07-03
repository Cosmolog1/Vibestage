<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\EventRepository;
use App\Repository\ArtisteRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class HomeController extends AbstractController
{
    #[Route('', name: 'home')]
    public function index(
        ArtisteRepository $artisteRepository,
        EventRepository $eventRepository,
        CommentRepository $commentRepository,

    ): Response {

        $artistes = $artisteRepository->findAll();
        $events = $eventRepository->findAll();
        $comments = $commentRepository->findAll();


        return $this->render('home/index.html.twig', [
            'artistes' => $artistes,
            'events' => $events,
            'comments' => $comments,
        ]);
    }

    #[Route('/single/{id}', name: 'single')] //, requirements: ['id' => '\d+'])
    public function single(
        int $id,
        ArtisteRepository $ArtisteRepository,
        EventRepository $EventRepository,
        request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $artiste = $ArtisteRepository->find($id);
        $event = $EventRepository->find($id);
        // dd($recipe);


        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEvent($event);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('single', [
                'id' => $id
            ]);
        }


        return $this->render('home/single.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'artiste' => $artiste
        ]);
    }
}
