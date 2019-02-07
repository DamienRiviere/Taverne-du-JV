<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration("Titre", "Tapez un titre pour votre article")
            )
            ->add(
                'content', 
                TextareaType::class, 
                $this->getConfiguration(false, "Ecrivez votre article", [
                    'attr' => [
                        'cols' => '15',
                        'rows' => '15'
                    ]
                ])
                
            )
            ->add(
                'coverImage', 
                UrlType::class, 
                $this->getConfiguration("URL de l'image", "Mettez l'URL de votre image")
            )
            ->add(
                'introduction', 
                TextType::class, 
                $this->getConfiguration("Introduction", "Ecrivez l'introduction de l'article")
            )
            ->add(
                'category', 
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'title',
                    'label' => 'Catégorie'
                ]
            )
            ->add(
                'subCategories',
                EntityType::class,
                [
                    'class' => SubCategory::class,
                    'choice_label' => 'title',
                    'expanded' => 'true',
                    'multiple' => 'true',
                    'label' => 'Plateforme'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
