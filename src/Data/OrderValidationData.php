<?php

namespace App\Data;

use App\Entity\Address;
use App\Entity\Shipper;

class OrderValidationData
{
    public ?Address $shippingAddress = null;
    public ?Address $paymentAddress = null;
    public ?Shipper $shipper = null;

    public function isEmpty(): bool
    {
        return $this->shippingAddress == null && $this->paymentAddress == null && $this->shipper == null;
    }
}
