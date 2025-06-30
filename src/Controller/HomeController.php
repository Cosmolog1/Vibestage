<?php

namespace App\Controller;

use App\Repository\ArtisteRepository;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
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
}
