<?php

namespace App\Form;

use App\Data\OrderValidationData;
use App\Entity\Address;
use App\Entity\Shipper;
use App\Entity\User;
use App\Repository\AddressRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ValidateOrderType extends AbstractType
{
    private User $user;

    public function __construct(TokenStorageInterface $token)
    {
        $this->user = $token->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $addressQueryBuilder = function (AddressRepository $addressRepository) {
            return $addressRepository->createQueryBuilder("a")
                ->where("a.isDeleted = 0")
                ->andWhere("a.user = :id")
                ->setParameter("id", $this->user->getId());
        };

        $builder
            ->add('shippingAddress', EntityType::class, [
                'label' => "Adresse de livraison",
                'class' => Address::class,
                'expanded' => true,
                'multiple' => false,
                'query_builder' => $addressQueryBuilder
            ])
            ->add('paymentAddress', EntityType::class, [
                'label' => "Adresse de facturation",
                'class' => Address::class,
                'expanded' => true,
                'multiple' => false,
                'query_builder' => $addressQueryBuilder
            ])
            ->add('shipper', EntityType::class, [
                'label' => "Livreur",
                'class' => Shipper::class,
                'expanded' => true,
                'multiple' => false,
                'choice_label' => function (Shipper $shipper) {
                    return $shipper->toStringWithPrice();
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider ma commande"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderValidationData::class,
        ]);
    }
}