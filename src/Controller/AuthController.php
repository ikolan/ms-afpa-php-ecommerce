<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use App\Form\UserRegistrationType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private AuthenticationUtils $authenticationUtils;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, AuthenticationUtils $authenticationUtils)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->authenticationUtils = $authenticationUtils;
    }

    #[Route('/auth', name: 'auth', methods: ["GET"])]
    public function auth(): Response
    {
        if ($this->getUser() != null) {
            return new RedirectResponse($this->generateUrl("user"));
        }

        $loginForm = $this->createForm(UserLoginType::class, new User());
        $registerForm = $this->createForm(UserRegistrationType::class, new User());

        return $this->render("auth/auth.html.twig", [
            "loginForm" => $loginForm->createView(),
            "registerForm" => $registerForm->createView(),
        ]);
    }

    #[Route("/auth/register", name: "auth_register", methods: ["POST"])]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userRepository = $this->entityManager->getRepository(User::class);
            if ($userRepository->findOneBy(["email" => $user->getEmail()]) != null) {
                return new RedirectResponse($this->generateUrl("auth", [
                    "emailAlreadyUse" => true
                ]));
            }
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return new RedirectResponse($this->generateUrl("auth", [
                "registrationSuccess" => true
            ]));
        }
    }

    #[Route("/auth/login", name: "auth_login", methods: ["GET", "POST"])]
    public function login(): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();

        return new RedirectResponse($this->generateUrl("auth", [
            "loginError" => $error ? $error->getMessage() : null
        ]));
    }

    #[Route("/auth/logout", name: "auth_logout", methods: ["GET"])]
    public function logout()
    {
    }
}
