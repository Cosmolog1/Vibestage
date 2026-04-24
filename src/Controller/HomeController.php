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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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
        $artistCategory = $request->query->get('artist_category');
        if ($artistCategory !== null && $artistCategory !== '') {
            $artistCategory = (int) $artistCategory;
        } else {
            $artistCategory = null;
        }

        $artistes = $artisteRepository->findByFilters($artistCountry, $artistCategory, 11);
        $eventCountry   = $request->query->get('event_country');

        // 🔹 Données filtrées

        $events   = $eventRepository->findByFilters($eventCountry, 11);
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
        CacheInterface $cache,

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

        // Appel de l'API Ticketmaster afin de trouver l'ID de l'artiste 
        $events = [];

        try { // Recuperation de l'Id de l'artiste
            $ticketmasterApiKey = $_ENV['TICKETMASTER_API_KEY'];

            // ⚡ Mise en cache de l'attractionId pour l'artiste
            $attractionId = $cache->get('ticketmaster_attraction_' . $artiste->getId(), function (ItemInterface $item)
            use ($client, $ticketmasterApiKey, $artiste) {
                $item->expiresAfter(86400); // 24h en cache

                $response = $client->request('GET', 'https://app.ticketmaster.com/discovery/v2/attractions.json', [
                    'query' => [
                        'apikey' => $ticketmasterApiKey,
                        'keyword' => $artiste->getName(),
                        'size' => 1,
                    ]
                ]);
                $data = $response->toArray();
                return $data['_embedded']['attractions'][0]['id'] ?? null;
            });

            if ($attractionId) {
                // ⚡ Mise en cache des events liés à l'attractionId
                $events = $cache->get('ticketmaster_events_' . $attractionId, function (ItemInterface $item)
                use ($client, $ticketmasterApiKey, $attractionId) {
                    $item->expiresAfter(3600); // 1h en cache
                    // Récupere les event de l'artiste
                    $response = $client->request('GET', 'https://app.ticketmaster.com/discovery/v2/events.json', [
                        'query' => [
                            'apikey' => $ticketmasterApiKey,
                            'attractionId' => $attractionId,
                            'size' => 6,
                        ]
                    ]);
                    $data = $response->toArray();
                    return $data['_embedded']['events'] ?? [];
                });
            }
        } catch (\Exception $e) {
            $events = [];
        }
        return $this->render('home/single_artiste.html.twig', [
            'form' => $form->createView(),
            'artiste' => $artiste,
            'comments' => $artiste->getComments(),
            'events' => $events,
        ]);
    }

    #[Route('/api/deezer/artist/search/{id}', name: 'api_deezer_artist_search')]
    public function deezerSearch(string $id, HttpClientInterface $client, ArtisteRepository $artisteRepository): JsonResponse
    {
        $artist = $artisteRepository->find($id);
        try {
            // Étape 1 : rechercher l'artiste par nom
            $searchRes = $client->request('GET', 'https://api.deezer.com/search/artist', [
                'query' => ['q' => $artist->getName()]
            ]);
            $searchData = $searchRes->toArray()['data'] ?? [];

            if (empty($searchData)) {
                return $this->json([]);
            }
            // Prend le premier ID Deezer
            $deezerId = $searchData[0]['id'];
            // Récupére les top titres
            $tracksRes = $client->request('GET', "https://api.deezer.com/artist/{$deezerId}/top?limit=6");
            $tracks = $tracksRes->toArray()['data'] ?? [];

            return $this->json($tracks);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Impossible de récupérer les titres Deezer',
                'message' => $e->getMessage()
            ], 500);
        }
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


    #[Route('/multi_artiste', name: 'multi_artiste')]
    public function multiArtiste(
        ArtisteRepository $artisteRepository,
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

        // 🔹 Données filtrées
        $artistes = $artisteRepository->findByFilters($artistCountry, $artistCategory);
        $comments = $commentRepository->findAll();

        // 🔹 Pour alimenter les <select>
        $countries  = $artisteRepository->findDistinctCountries();
        $categories = $categoryRepository->findAll();

        return $this->render('home/multi_artiste.html.twig', [
            'artistes'   => $artistes,
            'comments'   => $comments,
            'countries'  => $countries,
            'categories' => $categories,
        ]);
    }

    #[Route('/multi_event', name: 'multi_event')]
    public function multi_event(
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

        $eventCountry   = $request->query->get('event_country');

        // 🔹 Données filtrées

        $events   = $eventRepository->findByFilters($eventCountry);
        $comments = $commentRepository->findAll();

        // 🔹 Pour alimenter les <select>
        $countries  = $artisteRepository->findDistinctCountries();
        $categories = $categoryRepository->findAll();

        return $this->render('home/multi_event.html.twig', [
            'events'     => $events,
            'comments'   => $comments,
            'countries'  => $countries,
            'categories' => $categories,
        ]);
    }


    #[Route('/politics', name: 'politics')]
    public function politics(): Response
    {
        return $this->render('home/politics.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('home/cgu.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/copyright', name: 'copyright')]
    public function copyright(): Response
    {
        return $this->render('home/copyright.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


}
