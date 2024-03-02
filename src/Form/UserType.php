<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
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
                    new Regex('/^[a-zA-Z\-\' ]+$/' , message: 'The first name is invalid.')
                ]
            ])
            ->add('last_name' , TextType::class , [
                'constraints' => [
                    new Regex('/^[a-zA-Z\-\' ]+$/' , message: 'The last name is invalid.')
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
//            ->add('date_naissance', null ,[
//                'constraints' => [
//                    new LessThanOrEqual(['value' => new \DateTimeImmutable(), 'message' => 'Date of birth cannot be in the future']),
//                ],
//            ])
            ->add('image', FileType::class , [
//                'multiple'=>false,
                'mapped'=> false,
                'required'=> false,

            ])

//            ->add('maladie_chronique')
            ->add('num_tel2',IntegerType::class , [
                'invalid_message' => ' Le numéro de téléphone  n\'est pas valide.'
            ])
//            ->add('specialite', TextType::class , [
//                'constraints' => [
//                    new Regex('/^[a-zA-Z\-\' ]+$/' , message: ' Le prenom  n\'est pas valide.')
//                ],
//            ])
//            ->add('validation')
//            ->add('rate')
//            ->add('disponibilite')
//            ->add('date_debut',  TimeType::class)
//            ->add('date_fin',  TimeType::class)
            ->add('prix_c')
            ->add('diplomes', FileType::class ,[
//                'multiple'=>false,
                'mapped'=> false,
                'required'=> false,
            ] )
//            ->add('dure_rdv')
            ->add('allergies' )
            ->add('antecedent_maladie' )
            ->add('antecedent_medicaux')
//            ->add('groupe_sanguin')
            ->add('Submit' , SubmitType::class)
        ;

        if ($options['password']) {
            $builder->remove('email');
            $builder->remove('first_name');
            $builder->remove('last_name');
            $builder->remove('image');

            $builder->remove('num_tel');
            $builder->remove('diplomes');
            $builder->remove('prix_c');
//            $builder->remove('specialite');
            $builder->remove('num_tel2');
            $builder->remove('allergies');
            $builder->remove('antecedent_maladie');
            $builder->remove('antecedent_medicaux');
            $builder->remove('Submit');
        }
        if ($options['email_verify']) {
            $builder->remove('password');
            $builder->remove('first_name');
            $builder->remove('last_name');
            $builder->remove('image');

            $builder->remove('num_tel');
            $builder->remove('diplomes');
            $builder->remove('prix_c');
//            $builder->remove('specialite');
            $builder->remove('num_tel2');
            $builder->remove('allergies');
            $builder->remove('antecedent_maladie');
            $builder->remove('antecedent_medicaux');
            $builder->remove('Submit');
            $builder->remove('confirm_password');
        }

        if ($options['patient']) {
            $builder->remove('password');
            $builder->remove('confirm_password');
            $builder->remove('diplomes');
            $builder->remove('prix_c');
//            $builder->remove('specialite');
            $builder->remove('num_tel2');
            $builder->remove('Submit');
        }
        if ($options['patient_admin']) {
//            $builder->remove('password');
//            $builder->remove('confirm_password');
            $builder->remove('diplomes');
            $builder->remove('prix_c');
//            $builder->remove('specialite');
            $builder->remove('num_tel2');
            $builder->remove('Submit');
        }
        if ($options['expert']) {
            $builder->remove('image');
//            $builder->remove('num_tel');
            $builder->remove('diplomes');
            $builder->remove('prix_c');
//            $builder->remove('specialite');
            $builder->remove('num_tel2');
            $builder->remove('allergies');
            $builder->remove('antecedent_maladie');
            $builder->remove('antecedent_medicaux');
//            $builder->remove('Submit');
        }
        if ($options['expert_admin']) {
            $builder->remove('image');
//            $builder->remove('address');
//            $builder->remove('num_tel');
//            $builder->remove('password');
//            $builder->remove('confirm_password');
            $builder->remove('diplomes');
            $builder->remove('prix_c');
//            $builder->remove('specialite');
            $builder->remove('num_tel2');
            $builder->remove('allergies');
            $builder->remove('antecedent_maladie');
            $builder->remove('antecedent_medicaux');
            $builder->remove('Submit');
        }
        if ($options['medecin']) {
            $builder->remove('password');
            $builder->remove('confirm_password');
            $builder->remove('allergies');
            $builder->remove('antecedent_maladie');
            $builder->remove('antecedent_medicaux');
            $builder->remove('Submit');
        }
        if ($options['medecin_admin']) {
            $builder->remove('password');
            $builder->remove('confirm_password');
            $builder->remove('allergies');
            $builder->remove('antecedent_maladie');
            $builder->remove('antecedent_medicaux');
            $builder->remove('Submit');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password' => false,
            'patient' => false,
            'medecin' => false,
            'patient_admin' => false,
            'medecin_admin' => false,
            'expert'=> false,
            'expert_admin'=> false ,
            'email_verify'=> false
        ]);
    }



}
