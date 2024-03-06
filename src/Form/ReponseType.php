<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;


class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description_r', TextareaType::class, [
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
            'data_class' => Reponse::class,
        ]);
    }
}