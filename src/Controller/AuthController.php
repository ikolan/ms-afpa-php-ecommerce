<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/auth', name: 'auth')]
    public function auth(): Response
    {
        $loginForm = $this->createForm(LoginType::class, new User());
        $registerForm = $this->createForm(RegistrationType::class, new User());

        return $this->render("auth/auth.html.twig", [
            "loginForm" => $loginForm->createView(),
            "registerForm" => $registerForm->createView(),
        ]);
    }

    #[Route("/auth/register", name: "auth_register")]
    public function register(Request $request) {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setCreatedAt(new DateTimeImmutable());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return new RedirectResponse("/auth");
        }
    }
}
