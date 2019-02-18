<?php

namespace App\Form;

use App\Entity\Moderation;
use App\Form\ApplicationType;
use App\Entity\CommentArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminCommentArticleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'moderation',
                EntityType::class,
                [
                    'class' => Moderation::class,
                    'choice_label' => 'statut',
                    'label' => 'Statut du commentaire'
                ]
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
