<?php

namespace App\Controller;

use App\Data\Cart;
use App\Data\OrderValidationData;
use App\Form\ValidateOrderType;
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
        return $this->render("cart/cart.html.twig");
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

    #[Route("/cart/validateOrder", name: "cart_validateOrder", methods: ["GET", "POST"])]
    public function order(Request $request, Cart $cart): Response
    {
        if ($cart->count() <= 0) {
            return new RedirectResponse($this->generateUrl("cart"));
        }

        $orderValidationData = new OrderValidationData();
        $form = $this->createForm(ValidateOrderType::class, $orderValidationData);
        $form->handleRequest($request);

        return $this->render("cart/validateOrder.html.twig", [
            "form" => $form->createView(),
            "orderValidationData" => $orderValidationData
        ]);
    }
}
