<?php

namespace App\Form;

use App\Entity\ItemEntity;
use App\Entity\TargetEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TargetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('price')
            ->add('item', EntityType::class, [
                'class' => ItemEntity::class,
                'choice_label' => 'title',
            ])
            ->add('save', SubmitType::class, ['label' => 'Create target'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TargetEntity::class,
        ]);
    }
}
