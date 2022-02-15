<?php

namespace App\Controller;

use App\Data\Cart;
use App\Data\OrderValidationData;
use App\Entity\OrderLine;
use App\Form\ValidateOrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
    public function order(Request $request, Cart $cart, RequestStack $requestStack): Response
    {
        if ($cart->count() <= 0) {
            return new RedirectResponse($this->generateUrl("cart"));
        }

        $orderValidationData = new OrderValidationData();
        $form = $this->createForm(ValidateOrderType::class, $orderValidationData);
        $form->handleRequest($request);

        /** @var ?Order $order */
        $order = null;
        if ($request->isMethod("POST")) {
            $order = $orderValidationData->toOrder($this->getUser());
            $this->entityManager->persist($order);

            foreach ($cart->get() as $cartItem) {
                $orderLine = new OrderLine();
                $orderLine->setConcernedOrder($order);
                $orderLine->setProductName($cartItem["product"]->getName());
                $orderLine->setProductPrice($cartItem["product"]->getPrice());
                $orderLine->setProductQuantity($cartItem["quantity"]);
                $this->entityManager->persist($orderLine);
            }

            $this->entityManager->flush();
        }

        return $this->render("cart/validateOrder.html.twig", [
            "form" => $form->createView(),
            "orderValidationData" => $orderValidationData,
            "reference" => $order === null ? null : $order->getReference(),
        ]);
    }
}
