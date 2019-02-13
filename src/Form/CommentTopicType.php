<?php

namespace App\Form;

use App\Entity\CommentTopic;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentTopicType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration("Message", "Écrivez votre message ...", [
                    'attr' => [
                        'cols' => '10',
                        'rows' => '10'
                    ]
                ])
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentTopic::class,
        ]);
    }
}
