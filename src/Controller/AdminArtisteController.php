<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteFormType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminArtisteController extends AbstractController
{
    // Liste de tous les artistes
    #[Route('/admin/artiste', name: 'admin_artiste')]
    public function index(ArtisteRepository $artisteRepository): Response
    {
        return $this->render('admin_artiste/index.html.twig', [
            'artistes' => $artisteRepository->findAll(),
        ]);
    }

    // Affichage d'un artiste
    #[Route('/admin/artiste/{id}', name: 'admin_artiste_show')]
    public function show(int $id, ArtisteRepository $artisteRepository): Response
    {
        $artiste = $artisteRepository->find($id);
        if (!$artiste) {
            throw $this->createNotFoundException('Artiste non trouvé');
        }

        return $this->render('admin_artiste/show.html.twig', [
            'artiste' => $artiste,
        ]);
    }

    // Suppression d'un artiste
    #[Route('/admin/delete_artiste/{id}', name: 'admin_artiste_delete')]
    public function delete(
        int $id,
        ArtisteRepository $artisteRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $artiste = $artisteRepository->find($id);
        if (!$artiste) {
            throw $this->createNotFoundException('Artiste non trouvé');
        }

        $entityManager->remove($artiste);
        $entityManager->flush();

        $this->addFlash('success', 'Artiste supprimé avec succès.');
        return $this->redirectToRoute('admin_artiste');
    }

    // Édition d'un artiste
    #[Route('/admin/edit_artiste/{id}', name: 'admin_artiste_edit')]
    public function edit(
        int $id,
        ArtisteRepository $artisteRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $artiste = $artisteRepository->find($id);
        if (!$artiste) {
            throw $this->createNotFoundException('Artiste non trouvé');
        }

        $form = $this->createForm(ArtisteFormType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                // Suppression de l'ancienne image si elle existe
                if ($artiste->getImage()) {  
                    $oldFile = $this->getParameter('image_directory') . '/' . $artiste->getImage(); 
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
            
                // Traitement de la nouvelle image
                $newFilename = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('image_directory'), $newFilename);
                $artiste->setImage($newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Artiste modifié avec succès.');
            return $this->redirectToRoute('admin_artiste');
        }

        return $this->render('admin_artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    // Ajout d'un nouvel artiste
    #[Route('/admin/add_artiste', name: 'admin_artiste_add')]
    public function add(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteFormType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($artiste);
            $entityManager->flush();

            $this->addFlash('success', 'Artiste ajouté avec succès.');
            return $this->redirectToRoute('admin_artiste');
        }

        return $this->render('admin_artiste/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}



