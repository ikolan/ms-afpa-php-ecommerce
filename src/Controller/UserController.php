<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateUserNameType;
use App\Form\UpdateUserPasswordType;
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

    #[Route("/user/updateName", name: "user_updateNameForm", methods: ["GET"])]
    public function updateNameForm(): Response
    {
        /** @var mixed $user */
        $user = $this->getUser();
        $form = $this->createForm(UpdateUserNameType::class, $user);

        return $this->render("user/updateName.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route("/user/updateName", name: "user_updateName", methods: ["POST"])]
    public function updateName(Request $request): Response
    {
        /** @var mixed $user */
        $user = $this->getUser();
        $form = $this->createForm(UpdateUserNameType::class, $user);

        $form->handleRequest($request);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new RedirectResponse($this->generateUrl("user"));
    }

    #[Route("/user/updatePassword", name: "user_updatePasswordForm", methods: ["GET"])]
    public function updatePasswordForm(): Response
    {
        /** @var mixed $user */
        $user = $this->getUser();
        $form = $this->createForm(UpdateUserPasswordType::class, $user);

        return $this->render("user/updatePassword.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route("/user/updatePassword", name: "user_updatePassword", methods: ["POST"])]
    public function updatePassword(Request $request): Response
    {
        /** @var mixed $user */
        $user = $this->getUser();
        $userForm = new User();
        $form = $this->createForm(UpdateUserPasswordType::class, $userForm);
        $form->handleRequest($request);

        if ($this->passwordHasher->isPasswordValid($user, $form["currentPassword"]->getData())) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userForm->getPassword());
            $user->setPassword($hashedPassword);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new RedirectResponse($this->generateUrl("user", [
                "successMessage" => "Votre mot de passe à été modifié."
            ]));
        } else {
            return new RedirectResponse($this->generateUrl("user_updatePasswordForm", [
                "wrongCurrentPassword" => true
            ]));
        }
    }

    #[Route("/user/addAddress", name: "user_addAddressForm", methods: ["GET"])]
    public function addAddressForm(): Response
    {
        return $this->render("user/addAddress.html.twig");
    }
}
