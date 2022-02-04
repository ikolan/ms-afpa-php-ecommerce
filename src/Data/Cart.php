<?php

namespace App\Data;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private SessionInterface $requestStack;
    private ProductRepository $productRepository;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack->getSession();
        $this->productRepository = $entityManager->getRepository(Product::class);
    }

    public function get(): array
    {
        $cart = $this->requestStack->get("cart", []);
        $ids = array_map(function ($value) {
            return $value["id"];
        }, $cart);
        $products = $this->productRepository->findBy(["id" => $ids]);

        foreach ($cart as $i => $item) {
            $cart[$i]["cartId"] = $i;
            foreach ($products as $product) {
                if ($item["id"] == $product->getId()) {
                    $cart[$i]["product"] = $product;
                    break;
                }
            }
        }

        return $cart;
    }

    public function addItem(int $itemId, int $quantity)
    {
        $cart = $this->requestStack->get("cart", []);
        $cart[] = [
            "id" => $itemId,
            "quantity" => $quantity
        ];
        $this->requestStack->set("cart", $cart);
    }

    public function removeItem(int $cartId)
    {
        $cart = $this->requestStack->get("cart", []);
        unset($cart[$cartId]);
        $this->requestStack->set("cart", $cart);
    }

    public function changeQuantity(int $cartId, int $quantity)
    {
        $cart = $this->requestStack->get("cart", []);
        $cart[$cartId]["quantity"] = $quantity;
        $this->requestStack->set("cart", $cart);
    }

    public function clear()
    {
        $this->requestStack->remove("cart");
    }
}
