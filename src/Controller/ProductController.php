<?php

namespace App\Controller;

use App\Data\ProductFilterData;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductFilterType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $this->entityManager->getRepository(Product::class);
        $this->categoryRepository = $this->entityManager->getRepository(Category::class);
    }

    #[Route('/product', name: 'products', methods: ["GET"])]
    public function productList(Request $request): Response
    {
        $filterData = new ProductFilterData();
        $filterForm = $this->createForm(ProductFilterType::class, $filterData);
        $filterForm->handleRequest($request);

        $products = null;
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $products = $this->productRepository->findByFilter(
                $filterData->search,
                $filterData->categories
            );
        } else {
            $products = $this->productRepository->findAll();
        }

        $categories = $this->categoryRepository->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'filterForm' => $filterForm->createView()
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
}
