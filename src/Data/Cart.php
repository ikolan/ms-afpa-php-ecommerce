<?php

namespace App\Data;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private RequestStack $requestStack;
    private ProductRepository $productRepository;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $entityManager->getRepository(Product::class);
    }

    public function get(): array
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
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
        $cart = $this->requestStack->getSession()->get("cart", []);
        $cartId = -1;

        foreach ($cart as $i => $cartItem) {
            if ($cartItem["id"] === $itemId) {
                $cartId = $i;
            }
        }

        if ($cartId === -1) {
            $cart[] = [
                "id" => $itemId,
                "quantity" => $quantity
            ];
        } else {
            $cart[$cartId]["quantity"] += $quantity;
        }

        $this->requestStack->getSession()->set("cart", $cart);
    }

    public function removeItem(int $cartId)
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        unset($cart[$cartId]);
        $this->requestStack->getSession()->set("cart", $cart);
    }

    public function changeQuantity(int $cartId, int $quantity)
    {
        $cart = $this->requestStack->getSession()->get("cart", []);
        $cart[$cartId]["quantity"] = $quantity;
        $this->requestStack->getSession()->set("cart", $cart);
    }

    public function clear()
    {
        $this->requestStack->getSession()->remove("cart");
    }

    public function count(): int
    {
        $result = 0;
        foreach ($this->requestStack->getSession()->get("cart", []) as $item) {
            $result += $item["quantity"];
        }
        return $result;
    }
}
