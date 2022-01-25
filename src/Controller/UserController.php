<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/user', name: 'user', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('user/user.html.twig');
    }

    #[Route("/user/updatePassword", name: "user_updatePasswordForm", methods: ["GET"])]
    public function updatePasswordForm(): Response
    {
        return $this->render("user/updatePassword.html.twig");
    }

    #[Route("/user/updatePassword", name: "user_updatePassword", methods: ["POST"])]
    public function updatePassword(Request $request): Response
    {
        /** @var mixed $user */
        $user = $this->getUser();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $request->get("newPassword"));
        $user->setPassword($hashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new RedirectResponse($this->generateUrl("user"));
    }
}
