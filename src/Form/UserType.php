<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Regex(pattern:'/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,30}$/'
                        , message: 'Your password is faible with  Min 1 uppercase letter et Min 1 lowercase letter et Min 1 special character et Min 1 number et Min 8 characters et Max 30 characters )')
                ]
            ])
            ->add('confirm_password' , PasswordType::class)
            ->add('first_name' , TextType::class , [
                'constraints' => [
                    new Regex('/^[a-zA-Z\-\' ]+$/' , message: ' Le name  n\'est pas valide.')
                ]
            ])
            ->add('last_name' , TextType::class , [
                'constraints' => [
                    new Regex('/^[a-zA-Z\-\' ]+$/' , message: ' Le prenom  n\'est pas valide.')
                ]
            ])
//            ->add('role')
//            ->add('gender')
            ->add('num_tel' ,IntegerType::class , [
                'invalid_message' => ' Le numéro de téléphone  n\'est pas valide.'
            ])

//            ->add('date_create_compte')
//            ->add('last_modify_date')
//            ->add('last_modify_data')
//            ->add('date_naissance', DateType::class, [
//                'format' => 'dd-MM-yyyy',
//                'attr' => ['class' => 'datepicker'],
//            ])
            ->add('image', FileType::class)
            ->add('address')
//            ->add('maladie_chronique')
            ->add('num_tel2',IntegerType::class , [
                'invalid_message' => ' Le numéro de téléphone  n\'est pas valide.'
            ])
            ->add('specialite', TextType::class , [
                'constraints' => [
                    new Regex('/^[a-zA-Z\-\' ]+$/' , message: ' Le prenom  n\'est pas valide.')
                ],
            ])
//            ->add('validation')
//            ->add('rate')
//            ->add('disponibilite')
//            ->add('date_debut',  TimeType::class)
//            ->add('date_fin',  TimeType::class)
            ->add('prix_c')

            ->add('diplomes', FileType::class )
//            ->add('dure_rdv')
            ->add('allergies' )
            ->add('antecedent_maladie' )
            ->add('antecedent_medicaux')
//            ->add('groupe_sanguin')
            ->add('Submit' , SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
