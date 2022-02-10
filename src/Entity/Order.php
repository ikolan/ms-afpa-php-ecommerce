<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $shipperName;

    #[ORM\Column(type: 'float')]
    private $shipperPrice;

    #[ORM\Column(type: 'boolean')]
    private $isFinalized;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'text')]
    private $shippingAddress;

    #[ORM\Column(type: 'text')]
    private $paymentAddress;

    #[ORM\OneToMany(mappedBy: 'concernedOrder', targetEntity: OrderLine::class)]
    private $orderLines;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getShipperName(): ?string
    {
        return $this->shipperName;
    }

    public function setShipperName(string $shipperName): self
    {
        $this->shipperName = $shipperName;

        return $this;
    }

    public function getShipperPrice(): ?float
    {
        return $this->shipperPrice;
    }

    public function setShipperPrice(float $shipperPrice): self
    {
        $this->shipperPrice = $shipperPrice;

        return $this;
    }

    public function getIsFinalized(): ?bool
    {
        return $this->isFinalized;
    }

    public function setIsFinalized(bool $isFinalized): self
    {
        $this->isFinalized = $isFinalized;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getPaymentAddress(): ?string
    {
        return $this->paymentAddress;
    }

    public function setPaymentAddress(string $paymentAddress): self
    {
        $this->paymentAddress = $paymentAddress;

        return $this;
    }

    /**
     * @return Collection|OrderLine[]
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setConcernedOrder($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getConcernedOrder() === $this) {
                $orderLine->setConcernedOrder(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->reference;
    }
}
