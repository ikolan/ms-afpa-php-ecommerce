<?php

namespace App\Controller;

use App\Data\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route("/cart", name: "cart", methods: ["GET"])]
    public function cart(Cart $cart): Response
    {
        $items = $cart->get();

        return $this->render("cart/cart.html.twig", [
            "cart" => $items["items"],
            "cartPrice" => $items["price"],
        ]);
    }

    #[Route("/cart/add", name: "cart_add", methods: ["GET"])]
    public function add(Request $request, Cart $cart): Response
    {
        $cart->addItem($request->get("id"), $request->get("quantity"));
        return new RedirectResponse($this->generateUrl("cart"));
    }

    #[Route("/cart/remove", name: "cart_remove", methods: ["GET"])]
    public function remove(Request $request, Cart $cart): Response
    {
        $cart->removeItem($request->get("id"));
        return new RedirectResponse($this->generateUrl("cart"));
    }

    #[Route("/cart/changeQuantity", name: "cart_changeQuantity", methods: ["GET"])]
    public function changeQuantity(Request $request, Cart $cart): Response
    {
        $cart->changeQuantity($request->get("id"), $request->get("quantity"));
        return new RedirectResponse($this->generateUrl("cart"));
    }

    #[Route("/cart/clear", name: "cart_clear", methods: ["GET"])]
    public function clear(Cart $cart)
    {
        $cart->clear();
        return new RedirectResponse($this->generateUrl("cart"));
    }
}
