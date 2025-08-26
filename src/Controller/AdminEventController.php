<?php

namespace App\Controller;

use App\Form\EventFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class AdminEventController extends AbstractController
{
    #[Route('/admin/event', name: 'admin_event')]
    public function index(EventRepository $EventRepository): Response
    {
        $events = $EventRepository->findAll();

        return $this->render('admin_artiste/index.html.twig', [
            'events' => $events,
        ]);
    }

    // Création de la route qui affichera un event à l'admin une fois co

    #[Route('/admin/event/{id}', name: 'admin_event_show')]
    public function show($id, EventRepository $EventRepository): Response
    {
        $event = $EventRepository->find($id);

        return $this->render('admin_event/show.html.twig', [
            'event' => $event,
        ]);
    }

    // Création du CRUD delete qui supprimera un event si l'admin une fois co

    #[Route('/admin/delete_event/{id}', name: 'admin_event_delete')]
    public function delete(
        $id,
        EventRepository $EventRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $event = $EventRepository->find($id);

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->redirectToRoute('admin_event');
    }

    #[Route('/admin/edit_event/{id}', name: 'admin_event_edit')]
    public function edit(
        $id,
        EventRepository $EventRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/upload/image')] string $imagesDirectory
    ): Response {

        $edit = $EventRepository->find($id);
        $form = $this->createForm(EventFormType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $edit->setImage($newFilename);
            }

            $entityManager->persist($edit);
            $entityManager->flush();
        }

        return $this->render("admin_event/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }
}
