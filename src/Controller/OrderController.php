<?php

namespace App\Controller;

use App\Data\Cart;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\User;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Customer;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->orderRepository = $entityManager->getRepository(Order::class);
    }

    #[Route('/order/createStripeSession/{reference}', name: 'order_createStripeSession', methods: ["POST"])]
    public function createStripeSession(string $reference): Response
    {
        /** @var ?User $user */
        $user = $this->getUser();
        $YOUR_DOMAIN = "http://127.0.0.1:8000";
        $order = $this->orderRepository->findOneBy(["reference" => $reference]);
        $sessionProducts = [];

        /** @var OrderLine $orderLine */
        foreach ($order->getOrderLines()->getValues() as $orderLine) {
            $sessionProducts[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $orderLine->getProductPrice(),
                    'product_data' => [
                        'name' => $orderLine->getProductName()
                    ]
                ],
                'quantity' => $orderLine->getProductQuantity()
            ];
        }

        $sessionProducts[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getShipperPrice(),
                'product_data' => [
                    'name' => $order->getShipperName()
                ],
            ],
            'quantity' => 1
        ];

        Stripe::setApiKey('sk_test_51KT0tVInG8P9yZ7Is7aL0bY1B8eckSzT8RjlnWALv0GTDcLMeUOBHplH7HmtcaMLpZTX2F76NqtNnhmmC8Y4pW7F00dBYNFjA0');

        $customer = null;
        if ($user->getStripeId() === null) {
            $customer = Customer::create([
                "email" => $user->getEmail(),
                "name" => $user->getFullName(),
            ]);
            $user->setStripeId($customer->id);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } else {
            $customer = Customer::retrieve($user->getStripeId());
        }

        $checkoutSession = StripeSession::create([
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
            'line_items' => [$sessionProducts],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/order/paymentSuccess/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/order/paymentFailed/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeId($checkoutSession->id);
        $this->entityManager->flush();

        return new JsonResponse(["id" => $checkoutSession->id]);
    }

    #[Route("/order/paymentSuccess/{stripeSessionId}", name:"order_paymentSuccess", methods: ["GET"])]
    public function success(string $stripeSessionId, Cart $cart): Response
    {
        $order = $this->orderRepository->findOneBy(["stripeId" => $stripeSessionId]);

        if (!$order) {
            return new RedirectResponse($this->generateUrl("home"));
        }

        $cart->clear();
        $order->setIsFinalized(true);
        $this->entityManager->flush();

        return $this->render("cart/paymentSuccess.html.twig", [
            "order" => $order
        ]);
    }

    #[Route("/order/paymentFailed/{stripeSessionId}", name: "order_paymentFailed", methods: ["GET"])]
    public function failed(string $stripeSessionId): Response
    {
        $order = $this->orderRepository->findOneBy(["stripeId" => $stripeSessionId]);

        if (!$order) {
            return new RedirectResponse($this->generateUrl("home"));
        }

        return $this->render("cart/paymentFailed.html.twig", [
            "order" => $order
        ]);
    }
}
