<?php

namespace App\Form;

use App\Entity\ItemCsEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active')
            ->add('title')
            ->add('maxAmount')
            ->add('maxPrice')
            ->add('controllPrice')
            ->add('controllPriceStep')
            ->add('save', SubmitType::class, ['label' => 'Create item and target'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ItemCsEntity::class,
        ]);
    }
}
