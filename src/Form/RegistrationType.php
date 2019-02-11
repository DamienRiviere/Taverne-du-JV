<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username', 
                TextType::class, 
                $this->getConfiguration("Pseudo", "Votre pseudo ...")
            )
            ->add(
                'email', EmailType::class, 
                $this->getConfiguration("Email", "Votre email ...")
            )
            ->add(
                'picture', 
                UrlType::class, 
                $this->getConfiguration("Photo de profil", "URL de votre avatar (optionnel) ...", [
                    'required' => false
                ])
            )
            ->add(
                'hash', 
                PasswordType::class, 
                $this->getConfiguration("Mot de passe", "Choisissez un mot de passe !")
            )
            ->add(
                'passwordConfirm',
                PasswordType::class,
                $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe !")
            )
            ->add(
                'description', 
                TextareaType::class, 
                $this->getConfiguration("Description", "Ecrivez une description (optionnel) ....", [
                    'required' => false
                ])
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
