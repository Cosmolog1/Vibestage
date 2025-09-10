<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminCommentController extends AbstractController
{
    #[Route('/admin/comment', name: 'admin_comment')]
    public function index(CommentRepository $CommentRepository): Response
    {
        $comments = $CommentRepository->findAll();

        return $this->render('admin_comment/index.html.twig', [
            'controller_name' => 'AdminCommentController',
            'comments' => $comments
        ]);
    }

    #[Route('/admin/comment/{id}', name: 'admin_comment_show')]
    public function show($id, CommentRepository $CommentRepository): Response
    {
        $comment = $CommentRepository->find($id);

        return $this->render('admin_comment/show.html.twig', [
            'comment' => $comment
        ]);
    }


    #[Route('/admin/delete_comment/{id}', name: 'admin_comment_delete')]
    public function delete($id, CommentRepository $CommentRepository, EntityManagerInterface $entityManager): Response
    {
        $comment = $CommentRepository->find($id);

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('admin_comment');
    }
}
