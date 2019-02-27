<?php

namespace App\Form;

use App\Form\ApplicationType;
use App\Entity\CommentArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentArticleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration(false, "Écrivez votre commentaire ...", [
                    'attr' => [
                        'cols' => '5',
                        'rows' => '5'
                    ]
                ])
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentArticle::class,
        ]);
    }
}
