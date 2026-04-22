<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationFormType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminLocationController extends AbstractController
{
    #[Route('/admin/location', name: 'admin_location')]
    public function index(LocationRepository $LocationRepository): Response
    {
        $locations = $LocationRepository->findAll();

        return $this->render('admin_location/index.html.twig', [
            'controller_name' => 'AdminLocationController',
            'locations' => $locations
        ]);
    }


    #[Route('/admin/location/{id}', name: 'admin_location_show')]
    public function show($id, LocationRepository $LocationRepository): Response
    {
        $location = $LocationRepository->find($id);

        return $this->render('admin_location/show.html.twig', [
            'location' => $location
        ]);
    }


    #[Route('/admin/delete_location/{id}', name: 'admin_location_delete')]
    public function delete($id, LocationRepository $LocationRepository, EntityManagerInterface $entityManager): Response
    {
        $location = $LocationRepository->find($id);

        $entityManager->remove($location);
        $entityManager->flush();

        return $this->redirectToRoute('admin_location');
    }


    #[Route('/admin/edit_location/{id}', name: 'admin_location_edit')]
    public function edit(
        $id,
        LocationRepository $LocationRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        $edit = $LocationRepository->find($id);
        $form = $this->createForm(LocationFormType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edit);
            $entityManager->flush();

            return $this->redirectToRoute('admin_location');
        }

        return $this->render("admin_location/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }


    #[Route('/admin/add_location', name: 'admin_location_add')]
    public function add_loc(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $loc = new Location();
        $form = $this->createForm(LocationFormType::class, $loc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($loc);
            $entityManager->flush();

            return $this->redirectToRoute('admin_location');
        }



        return $this->render('admin_location/add.html.twig', [
            'controller_name' => 'AdminLocationController',
            'form' => $form->createView(),

        ]);
    }
}
