<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('duree_maladie')
            ->add('poids')
            ->add('taille')
            ->add('temperature')
            ->add('frequence_cardique')
            ->add('respiration')
            ->add('conseils')
            ->add('medicament')
            ->add('date_prochaine')
            ->add('rdv',EntityType::class,['class' => 'App\Entity\RendezVous',
                'choice_label' => 'description'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
