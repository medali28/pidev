<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('medecin', TextType::class , [
                    'constraints' => [
                        new Regex('/^[a-zA-Z\-\' ]+$/' , message: 'le nom du medecin est incorrecte.')
                    ]
                ]
            )
            ->add('sujet', TextType::class , [
                    'constraints' => [
                        new Regex('/^[a-zA-Z\-\' ]+$/' , message: 'le sujet est incorrecte.')
                    ]
                ]
            )
            ->add('description_rec')
//            ->add('avis')
//            ->add('patient')
        ->add('submit',SubmitType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
