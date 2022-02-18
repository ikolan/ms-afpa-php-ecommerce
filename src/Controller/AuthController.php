<?php

namespace App\Controller;

use App\Data\Mailing;
use App\Data\ResetPasswordCode;
use App\Data\ResetPasswordData;
use App\Entity\User;
use App\Form\FinalizeResetPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserLoginType;
use App\Form\UserRegistrationType;
use App\Repository\UserRepository;
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
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, AuthenticationUtils $authenticationUtils)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->authenticationUtils = $authenticationUtils;
        $this->userRepository = $entityManager->getRepository(User::class);
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
            /** @var User $user */
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

            $mailing = new Mailing();
            $mailing->send(3645377, $user->getEmail(), $user->getFullName(), "Bienvenue " . $user->getFullName(), [
                "customerName" => $user->getFullName()
            ]);

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

    #[Route("/auth/resetPassword", name: "auth_resetPasswordForm", methods: ["GET"])]
    public function resetPasswordForm(): Response
    {
        $form = $this->createForm(ResetPasswordType::class);

        return $this->render("auth/resetPassword.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route("/auth/resetPassword", name: "auth_resetPassword", methods: ["POST"])]
    public function resetPassword(Request $request): Response
    {
        $resetPasswordData = new ResetPasswordData();
        $resetPasswordForm = $this->createForm(ResetPasswordType::class, $resetPasswordData);
        $resetPasswordForm->handleRequest($request);
        $user = $this->userRepository->findOneBy(["email" => $resetPasswordData->email]);

        if ($user === null) {
            return new RedirectResponse($this->generateUrl("auth"));
        }

        $user->setIsOnResetPassword(true);
        $user->setLastResetPaswordCode(new ResetPasswordCode);
        $this->entityManager->flush();

        $mailing = new Mailing();
        $mailing->send(3646030, $user->getEmail(), $user->getFullName(), "Mot de passe oublié", [
            "link" => $this->generateUrl("auth_finalizeResetPasswordForm", [
                "id" => $user->getId(),
                "code" => $user->getLastResetPaswordCode()
            ])
        ]);

        return new RedirectResponse($this->generateUrl("auth"));
    }

    #[Route("/auth/finalizeResetPassword/{id}/{code}", name: "auth_finalizeResetPasswordForm", methods: ["GET"])]
    public function finalizeResetPasswordForm(int $id, string $code): Response
    {
        $user = $this->userRepository->find($id);
        if ($user === null || !$user->getIsOnResetPassword() || $user->getLastResetPaswordCode() !== $code) {
            return new RedirectResponse($this->generateUrl("auth"));
        }

        $form = $this->createForm(FinalizeResetPasswordType::class);

        return $this->render("auth/finalizeResetPassword.html.twig", [
            "id" => $id,
            "code" => $code,
            "form" => $form->createView()
        ]);
    }

    #[Route("/auth/finalizeResetPassword/{id}/{code}", name: "auth_finalizeResetPassword", methods: ["POST"])]
    public function finalizeResetPassword(Request $request, int $id, string $code): Response
    {
        $user = $this->userRepository->find($id);
        if ($user === null || !$user->getIsOnResetPassword() || $user->getLastResetPaswordCode() !== $code) {
            return new RedirectResponse($this->generateUrl("auth"));
        }

        $userForm = new User();
        $form = $this->createForm(FinalizeResetPasswordType::class, $userForm);
        $form->handleRequest($request);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $userForm->getPassword());
        $user->setPassword($hashedPassword);
        $user->setIsOnResetPassword(false);
        $user->setLastResetPaswordCode(null);
        $this->entityManager->flush();

        return new RedirectResponse($this->generateUrl("auth"));
    }
}
