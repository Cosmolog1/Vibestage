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
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
        EntityManagerInterface $entityManager,
        HttpClientInterface $client,

    ): Response {
        $artiste = $ArtisteRepository->find($id);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArtiste($artiste);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable()); // pour la création
            $comment->setUpdateAt(new \DateTimeImmutable());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('single_artiste', [
                'id' => $id
            ]);
        }

        // ----- Appel API Ticketmaster pour trouver l'ID de l'artiste -----
        $events = [];
        try {
            // Étape 1 : récupérer l'ID de l'artiste
            $responseAttraction = $client->request('GET', 'https://app.ticketmaster.com/discovery/v2/attractions.json', [
                'query' => [
                    'apikey' => 'aKB2tWKGljWyA1sdoQdO5GWA7dNZOrJY', // ta clé Ticketmaster
                    'keyword' => $artiste->getName(),
                    'size' => 1,
                ]
            ]);

            $dataAttraction = $responseAttraction->toArray();
            $attractionId = $dataAttraction['_embedded']['attractions'][0]['id'] ?? null;

            if ($attractionId) {
                // Étape 2 : récupérer les events pour cet artiste
                $responseEvents = $client->request('GET', 'https://app.ticketmaster.com/discovery/v2/events.json', [
                    'query' => [
                        'apikey' => 'aKB2tWKGljWyA1sdoQdO5GWA7dNZOrJY',
                        'attractionId' => $attractionId,
                        'size' => 20,

                    ]
                ]);

                $dataEvents = $responseEvents->toArray();
                $events = $dataEvents['_embedded']['events'] ?? [];
            }
        } catch (\Exception $e) {
            $events = [];
        }



        return $this->render('home/single_artiste.html.twig', [
            'form' => $form->createView(),
            'artiste' => $artiste,
            'comments' => $artiste->getComments(),
            'events' => $events
        ]);
    }


    #[Route('/single_event/{id}', name: 'single_event')]
    public function singleEvent(
        int $id,
        EventRepository $EventRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $event = $EventRepository->find($id);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Formulaire commentaire
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEvent($event);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUpdateAt(new \DateTimeImmutable());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('single_event', ['id' => $id]);
        }

        // Rendu final
        return $this->render('home/single_event.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'comments' => $event->getComments(),
        ]);
    }
}
