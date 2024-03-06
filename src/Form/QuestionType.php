<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le Titre est un champ obligatoire']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => "Le titre '{{ value }}' ne doit contenir que des lettres et espaces."
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Votre Image (JPG,JPAG,PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '5124k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Déposez votre image.',
                    ])
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description est un champ obligatoire']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\/\*\x22\x27\(\)&{}\?!:,\s]+$/',
                        'message' => "La contenu de votre question '{{ value }}' ne doit contenir que des lettres, des chiffres et les caractères spécifiques autorisés."
                    ]),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}