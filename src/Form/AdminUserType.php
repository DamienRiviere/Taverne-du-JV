<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'userRoles',
                EntityType::class,
                [
                    'class' => Role::class,
                    'choice_label' => 'title',
                    'expanded' => 'true',
                    'multiple' => 'false',
                    'label' => 'Choix d\'un rôle'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
