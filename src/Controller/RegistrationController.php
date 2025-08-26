<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $pseudo = $request->request->get('pseudo');

        if (!$email || !$password) {
            $this->addFlash('error', 'Email et mot de passe sont requis.');
            return $this->redirectToRoute('app_login');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPseudo($pseudo); // si le champ existe
        $user->setPassword($userPasswordHasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_USER']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $security->login($user, AppCustomAuthenticator::class, 'main');
    }
}
