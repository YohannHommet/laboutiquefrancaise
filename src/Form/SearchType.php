<?php

namespace App\Form;


use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label'=> false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search here...',
                    'class' => "mt-5 mb-2 form-control-sm"
                ]
            ])
            ->add('categories', EntityType::class, [
                'label'=> false,
                'required' => false,
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => "ps-2"
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter',
                'attr' => [
                    'class' => 'btn btn-outline-info mt-2 px-5'
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}