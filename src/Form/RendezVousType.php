<?php

namespace App\Form;

use App\Entity\RendezVous;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
     {

             $builder
                 ->add('date', DateType::class, [
                     'required' => true,
                 ])
                 ->add('time', TimeType::class, [
                     'required' => true,
                 ])
                 ->add('description', TextareaType::class, [
                     'required' => true,
                     'attr' => ['placeholder' => 'Tell me about the project'],
                 ])
                 ->add('urgence', CheckboxType::class, [
                     'required' => false,
                     'label' => 'Rendez-vous en urgence',
                 ]);

     }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
