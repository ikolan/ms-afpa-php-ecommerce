<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddAddressType;
use App\Form\UpdateAddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AddressRepository $addressRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->addressRepository = $entityManager->getRepository(Address::class);
    }

    #[Route("/address/add", name: "address_addForm", methods: ["GET"])]
    public function addAddressForm(): Response
    {
        $form = $this->createForm(AddAddressType::class, new Address());
        return $this->render("address/add.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/address/add", name: "address_add", methods: ["POST"])]
    public function addAddress(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddAddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();

            return new RedirectResponse($this->generateUrl("user"));
        }
    }

    #[Route("/address/update/{id}", name: "address_updateForm", methods: ["GET"])]
    public function updateAddressForm(int $id): Response
    {
        $address = $this->addressRepository->findOneBy(["id" => $id, "isDeleted" => false]);

        if ($address === null || $address->getUser() != $this->getUser()) {
            return new RedirectResponse($this->generateUrl("user"));
        }

        $form = $this->createForm(UpdateAddressType::class, $address);

        return $this->render("address/update.html.twig", [
            "form" => $form->createView()
        ]);
    }

    #[Route("/address/update/{id}", name: "address_update", methods: ["POST"])]
    public function updateAddress(Request $request, int $id): Response
    {
        $address = $this->addressRepository->findOneBy(["id" => $id, "isDeleted" => false]);

        if ($address === null || $address->getUser() != $this->getUser()) {
            return new RedirectResponse($this->generateUrl("user"));
        }

        $form = $this->createForm(UpdateAddressType::class, $address);
        $form->handleRequest($request);
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return new RedirectResponse($this->generateUrl("user"));
    }

    #[Route("/address/delete/{id}", name: "address_delete", methods: ["GET"])]
    public function deleteAddress(int $id): Response
    {
        $address = $this->addressRepository->findOneBy(["id" => $id]);

        if ($address != null && $address->getUser() == $this->getUser()) {
            $address->setIsDeleted(true);
            $this->entityManager->persist($address);
            $this->entityManager->flush();
        }

        return new RedirectResponse($this->generateUrl("user"));
    }
}
