<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ObjectRepository $productRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $this->entityManager->getRepository(Product::class);
    }

    #[Route('/product', name: 'products', methods: ["GET"])]
    public function productList(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route("/product/json/{id}", name: "product_json", methods: ["GET"])]
    public function productJson(int $id): Response
    {
        $product = $this->productRepository->findOneBy(["id" => $id]);
        return new JsonResponse([
            "id" => $product->getId(),
            "slug" => $product->getSlug(),
            "name" => $product->getName(),
            "description" => $product->getDescription(),
            "price" => $product->getPrice(),
        ]);
    }

    #[Route('/product/{id}/{slug}', name: 'product_detail', methods: ["GET"])]
    public function productPage(int $id, string $slug): Response
    {
        $product = $this->productRepository->findOneBy(["id" => $id]);

        if ($product->getSlug() != $slug) {
            return new RedirectResponse($this->generateUrl("products"));
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route("/product/cart", name: "product_cart", methods: ["GET"])]
    public function cart(): Response
    {
        return $this->render("product/cart.html.twig");
    }
}
