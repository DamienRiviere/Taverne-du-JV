<?php

namespace App\Form;

use App\Entity\Topic;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TopicType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration("Titre du topic", "Écrivez le titre du topic ...")
            )
            ->add(
                'message',
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
            'data_class' => Topic::class,
        ]);
    }
}
