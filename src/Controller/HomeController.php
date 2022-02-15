<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->productRepository = $entityManager->getRepository(Product::class);
    }

    #[Route('/', name: 'home', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            "bestProducts" => $this->productRepository->findBest()
        ]);
    }
}
