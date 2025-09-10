<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\EventRepository;
use App\Repository\ArtisteRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('', name: 'home')]
    public function index(
        ArtisteRepository $artisteRepository,
        EventRepository $eventRepository,
        CommentRepository $commentRepository,
        CategoryRepository $categoryRepository,
        Request $request
    ): Response {
        // 🔹 Récupération des filtres
        $artistCountry = $request->query->get('artist_country');
        $artistCategory = $request->query->get('artist_category'); // correspond à un ID
        $artistCategory = $request->query->get('artist_category');
        if ($artistCategory !== null && $artistCategory !== '') {
            $artistCategory = (int) $artistCategory;
        } else {
            $artistCategory = null;
        }

        $artistes = $artisteRepository->findByFilters($artistCountry, $artistCategory);
        $eventCountry   = $request->query->get('event_country');

        // 🔹 Données filtrées
        $artistes = $artisteRepository->findByFilters($artistCountry, $artistCategory);
        $events   = $eventRepository->findByFilters($eventCountry);
        $comments = $commentRepository->findAll();

        // 🔹 Pour alimenter les <select>
        $countries  = $artisteRepository->findDistinctCountries();
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'artistes'   => $artistes,
            'events'     => $events,
            'comments'   => $comments,
            'countries'  => $countries,
            'categories' => $categories,
        ]);
    }

    #[Route('/single_artiste/{id}', name: 'single_artiste')] //, requirements: ['id' => '\d+'])
    public function singleArtiste(
        int $id,
        ArtisteRepository $ArtisteRepository,
        request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $artiste = $ArtisteRepository->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArtiste($artiste);
            $comment->setUpdateAt(new \DateTimeImmutable());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('single_artiste', [
                'id' => $id
            ]);
        }


        return $this->render('home/single_artiste.html.twig', [
            'form' => $form->createView(),
            'artiste' => $artiste
        ]);
    }


    #[Route('/single_event/{id}', name: 'single_event')] //, requirements: ['id' => '\d+'])
    public function singleEvent(
        int $id,
        EventRepository $EventRepository,
        request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $event = $EventRepository->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEvent($event);
            $comment->setUpdateAt(new \DateTimeImmutable());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('single_event', [
                'id' => $id
            ]);
        }


        return $this->render('home/single_event.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }
}
