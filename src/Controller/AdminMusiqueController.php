<?php

namespace App\Controller;

use App\Entity\Musique;
use App\Form\MusiqueFormType;
use App\Repository\MusiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminMusiqueController extends AbstractController
{
    #[Route('/admin/musique', name: 'admin_musique')]
    public function index(MusiqueRepository $MusiqueRepository): Response
    {
        $musiques = $MusiqueRepository->findAll();

        return $this->render('admin_musique/index.html.twig', [
            'controller_name' => 'AdminMusiqueController',
            'musiques' => $musiques
        ]);
    }


    #[Route('/admin/musique/{id}', name: 'admin_musique_show')]
    public function show($id, MusiqueRepository $MusiqueRepository): Response
    {
        $musique = $MusiqueRepository->find($id);

        return $this->render('admin_difficult/show.html.twig', [
            'musique' => $musique
        ]);
    }


    #[Route('/admin/delete_musique/{id}', name: 'admin_musique_delete')]
    public function delete($id, MusiqueRepository $MusiqueRepository, EntityManagerInterface $entityManager): Response
    {
        $musique = $MusiqueRepository->find($id);

        $entityManager->remove($musique);
        $entityManager->flush();

        return $this->redirectToRoute('admin_musique');
    }


    #[Route('/admin/edit_musique/{id}', name: 'admin_musique_edit')]
    public function edit(
        $id,
        MusiqueRepository $MusiqueRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        $edit = $MusiqueRepository->find($id);
        $form = $this->createForm(MusiqueFormType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edit);
            $entityManager->flush();

            return $this->redirectToRoute('admin_musique');
        }

        return $this->render("admin_musique/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }


    #[Route('/admin/add_musique', name: 'admin_musique_add')]
    public function add_mus(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $mus = new Musique();
        $form = $this->createForm(MusiqueFormType::class, $mus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mus);
            $entityManager->flush();

            return $this->redirectToRoute('admin_musique');
        }



        return $this->render('admin_musique/add.html.twig', [
            'controller_name' => 'AdminDifficultController',
            'form' => $form->createView(),

        ]);
    }
}
