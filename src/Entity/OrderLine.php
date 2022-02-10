<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderLineRepository::class)]
class OrderLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $productName;

    #[ORM\Column(type: 'integer')]
    private $productQuantity;

    #[ORM\Column(type: 'float')]
    private $productPrice;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderLines')]
    #[ORM\JoinColumn(nullable: false)]
    private $concernedOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $productQuantity): self
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getConcernedOrder(): ?Order
    {
        return $this->concernedOrder;
    }

    public function setConcernedOrder(?Order $concernedOrder): self
    {
        $this->concernedOrder = $concernedOrder;

        return $this;
    }

    public function __toString()
    {
        return $this->productQuantity . " x " . $this->productName .
            "(" . $this->productPrice / 100 . " €) = " .
            ($this->productQuantity * $this->productPrice) / 100 . " €";
    }
}
