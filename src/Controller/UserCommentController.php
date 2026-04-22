<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserCommentController extends AbstractController
{
    #[Route('/user/comment', name: 'user_comment')]
    public function index(CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();

        $comments = $commentRepository->findBy(['user' => $user]);

        return $this->render('user_comment/index.html.twig', [
            'comments' => $comments
        ]);
    }

    #[Route('/user/comment/{commentId}', name: 'user_comment_show')]
    public function show(int $commentId, CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->find($commentId);

        // sécurité : on s'assure que le commentaire appartient bien à l'user connecté
        if (!$comment || $comment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas accès à ce commentaire.");
        }

        return $this->render('user_comment/show.html.twig', [
            'comment' => $comment
        ]);
    }

    #[Route('/user/comment/{commentId}/delete', name: 'user_comment_delete', methods: ['POST'])]
    public function delete(
        int $commentId,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $comment = $commentRepository->find($commentId);

        if ($comment && $comment->getUser() === $this->getUser()) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_comment');
    }

    #[Route('/user/comment/delete-multiple', name: 'user_comment_delete_multiple', methods: ['POST'])]
    public function deleteMultiple(
        Request $request,
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $commentIds = $request->get('comments', []);

        foreach ($commentIds as $id) {
            $comment = $commentRepository->find($id);
            if ($comment && $comment->getUser() === $this->getUser()) {
                $entityManager->remove($comment);
            }
        }

        $entityManager->flush();

        return $this->redirectToRoute('user_comment');
    }
}
