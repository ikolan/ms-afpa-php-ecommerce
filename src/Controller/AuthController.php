<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use App\Form\UserRegistrationType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/auth', name: 'auth', methods: ["GET"])]
    public function auth(): Response
    {
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

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setCreatedAt(new DateTimeImmutable());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return new RedirectResponse("/auth");
        }
    }

    #[Route("/auth/login", name: "auth_login", methods: ["POST"])]
    public function login(): Response
    {
        return new JsonResponse(null);
    }
}
