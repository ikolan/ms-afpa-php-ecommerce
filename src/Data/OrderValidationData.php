<?php

namespace App\Data;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\Shipper;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

class OrderValidationData
{
    public ?Address $shippingAddress = null;
    public ?Address $paymentAddress = null;
    public ?Shipper $shipper = null;

    public function isEmpty(): bool
    {
        return $this->shippingAddress == null && $this->paymentAddress == null && $this->shipper == null;
    }

    public function toOrder(User $user): Order
    {
        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setReference($order->getCreatedAt()->format("dmY") . "-" . uniqid());
        $order->setUser($user);
        $order->setShipperName($this->shipper->getName());
        $order->setShipperPrice($this->shipper->getPrice());
        $order->setShippingAddress($this->shippingAddress);
        $order->setPaymentAddress($this->paymentAddress);
        $order->setIsFinalized(false);
        return $order;
    }
}
