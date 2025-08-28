<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Artiste;
use App\Form\ArtisteFormType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


final class AdminArtisteController extends AbstractController
{
    // Création de la route qui affichera tout les artistes à l'admin une fois co

    #[Route('/admin/artiste', name: 'admin_artiste')]
    public function index(ArtisteRepository $ArtisteRepository): Response
    {
        $artistes = $ArtisteRepository->findAll();

        return $this->render('admin_artiste/index.html.twig', [
            'artistes' => $artistes,
        ]);
    }

    // Création de la route qui affichera un artiste à l'admin une fois co

    #[Route('/admin/artiste/{id}', name: 'admin_artiste_show')]
    public function show($id, ArtisteRepository $ArtisteRepository): Response
    {
        $artiste = $ArtisteRepository->find($id);

        return $this->render('admin_artiste/show.html.twig', [
            'artiste' => $artiste,
        ]);
    }

    // Création du CRUD delete qui supprimera un artiste si l'admin une fois co

    #[Route('/admin/delete_artiste/{id}', name: 'admin_artiste_delete')]
    public function delete(
        $id,
        ArtisteRepository $ArtisteRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $artiste = $ArtisteRepository->find($id);

        $entityManager->remove($artiste);
        $entityManager->flush();

        return $this->redirectToRoute('admin_artiste');
    }

    #[Route('/admin/edit_artiste/{id}', name: 'admin_artiste_edit')]
    public function edit(
        $id,
        ArtisteRepository $ArtisteRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/upload/image')] string $imagesDirectory
    ): Response {

        $edit = $ArtisteRepository->find($id);
        $form = $this->createForm(ArtisteFormType::class, $edit);
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

            return $this->redirectToRoute('admin_artiste');
        }

        return $this->render("admin_artiste/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/add_artiste', name: 'admin_artiste_add')]
    public function add_art(
        EntityManagerInterface $entityManager,
        Request $request,

    ): Response {
        $art = new Artiste();
        $form = $this->createForm(ArtisteFormType::class, $art);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($art);
            $entityManager->flush();

            return $this->redirectToRoute('admin_artiste');
        }


        return $this->render('admin_artiste/add.html.twig', [
            'controller_name' => 'AdminArtisteController',
            'form' => $form->createView(),

        ]);
    }
}
