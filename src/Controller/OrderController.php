<?php

namespace App\Controller;

use App\Data\Cart;
use App\Data\OrderValidationData;
use App\Entity\OrderLine;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/order', name: 'order', methods: ["POST"])]
    public function order(Cart $cart, RequestStack $requestStack): Response
    {
        /** @var OrderValidationData */
        $orderValidationData = $requestStack->getSession()->get("lastOrderValidationData");
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

        return new RedirectResponse($this->generateUrl("home"));
    }
}
