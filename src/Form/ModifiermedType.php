<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Medicament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifiermedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('Description')
            ->add('image')
            ->add('posted_at')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom_ctg', // Use the 'name' property of the Category entity as the label
            ])
            ->add('modifier',SubmitType::class);
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }
}
