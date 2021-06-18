<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control'
                ],
                'required' => true,
                'constraints' => [
                    new Length(['min' => 2])
                ]
            ]
            )
            ->add('surname', TextType::class,
            [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Length(['min' => 2])
                ]
            ]
            )
            ->add('nationality')
            ->add('photo', UrlType::class)
            ->add('age')
            ->add('movies', EntityType::class,
            [
                'label' => 'Films (censure est à vide)',
                'class' => Movie::class,

                'choice_label' => 'title',
                'multiple' => true,

                // Query builder pour choisir les movies à afficher
                'query_builder' => function(MovieRepository $movieRepository){
                    return $movieRepository->findByEmptyCensorshipQueryBuilder();
                }
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actor::class,
        ]);
    }
}
