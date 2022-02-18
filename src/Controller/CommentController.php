<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $entityManager->getRepository(Product::class);
    }

    #[Route("/comment", name: "comment_create", methods: ["POST"])]
    public function add(Request $request): Response
    {
        if ($this->getUser() === null) {
            return new RedirectResponse($this->generateUrl("home"));
        }
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);
        $product = $this->productRepository->find($form->get("productId")->getData());
        $user = $this->getUser();

        /** @var Comment $comment */
        $comment = $form->getData();
        $comment->setProduct($product);
        $comment->setUser($user);
        $comment->setCreatedAt(new DateTimeImmutable());
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new RedirectResponse($this->generateUrl("product_detail", ["id" => $product->getId(), "slug" => $product->getSlug()]));
    }
}
