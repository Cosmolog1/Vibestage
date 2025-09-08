<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminCategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'admin_category')]
    public function index(CategoryRepository $CategoryRepository): Response
    {
        $categories = $CategoryRepository->findAll();

        return $this->render('admin_category/index.html.twig', [
            'controller_name' => 'AdminCategoryController',
            'categories' => $categories
        ]);
    }


    #[Route('/admin/category/{id}', name: 'admin_category_show')]
    public function show($id, CategoryRepository $CategoryRepository): Response
    {
        $category = $CategoryRepository->find($id);

        return $this->render('admin_category/show.html.twig', [
            'category' => $category
        ]);
    }


    #[Route('/admin/delete_category/{id}', name: 'admin_category_delete')]
    public function delete($id, CategoryRepository $CategoryRepository, EntityManagerInterface $entityManager): Response
    {
        $category = $CategoryRepository->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('admin_category');
    }


    #[Route('/admin/edit_category/{id}', name: 'admin_category_edit')]
    public function edit(
        $id,
        CategoryRepository $CategoryRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        $edit = $CategoryRepository->find($id);
        $form = $this->createForm(CategoryFormType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edit);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category');
        }

        return $this->render("admin_category/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }


    #[Route('/admin/add_category', name: 'admin_category_add')]
    public function add_mus(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $mus = new Category();
        $form = $this->createForm(CategoryFormType::class, $mus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mus);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category');
        }



        return $this->render('admin_category/add.html.twig', [
            'controller_name' => 'AdminCategoryController',
            'form' => $form->createView(),

        ]);
    }
}
