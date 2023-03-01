<?php

namespace App\Form;

use Assert\NotBlank;
use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'font-weight:bold'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(min:2, max:50)
                ]
            ])
            ->add('executionTime', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                    'max' => '1440'
                ],
                'label' => 'Temps de préparation (en minutes)',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'font-weight:bold'
                ],
                'constraints' => [
                    new Assert\GreaterThanOrEqual(1),
                    new Assert\LessThan(1440)
                ],
                'required' => false
            ])
            ->add('nbPersonnes', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                    'max' => '1440'
                ],
                'label' => 'Nombre de couverts',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'font-weight:bold'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(50)
                ],
                'required' => false
            ])            
            ->add('Price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'font-weight:bold'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1000)
                ],
                'required' => false
           

            ]) 
            ->add('Favorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input ms-3'                    
                ],
                'label' => 'Recette favorite?',                
                'label_attr' => [
                    'class' => 'form-check-label',
                    'style' => 'font-weight:bold'
                ],
                'constraints' => [
                    new Assert\NotNull()                    
                ],
                'required' => false
            ])
            #Comme IngredientsIn est lié à l'entité Ingredient, on utilise EntityType
            ->add('IngredientsIn', EntityType::class, [
                #On se place dans l'entité choisie
                'class' => Ingredient::class,
                #On utilise query_builder pour personnaliser la requête.
                #Ici je veux que ma liste soit ordonnée alphabétiquement
                'query_builder' => function (IngredientRepository $ir) {
                    return $ir->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                #On utilise Ingredient.name comme propriété à afficher
                'choice_label' => 'name',
                #Pour avoir l'affichage d'une liste de checkboxs
                'multiple' => true,
                'expanded' =>true,
                'label' => 'Ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'font-weight:bold'
                ]

            ])
            ->add('Description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'style' => 'font-weight:bold'
                ],
                'constraints' => [
                    new Assert\NotBlank()                    
                ]
            ])
            ->add('Difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',                    
                    'min'=>"1",
                    'max'=>"5",
                    'step'=>"1"
                ],
                'label' => 'Difficulté (1 à 5)',
                'label_attr' =>[
                    'class' => 'form-label',
                    'style' => 'font-weight:bold'
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThanOrEqual(5)
                ],
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' =>'btn btn-info mt-3'
                ],
                'label' => 'Créer cette recette'
           
            ]);        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
