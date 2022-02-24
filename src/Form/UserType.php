<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private function getConfig($placeholder,$label){
        return [
    
            'attr' => [
                'placeholder' => $placeholder
            ],
            'label' => $label 
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, $this->getConfig("entrez votre nom","Nom"))
            ->add('firstname', TextType::class, $this->getConfig("entrez votre prenom","Prenom"))
            ->add('email', EmailType::class, $this->getConfig("entrez votre e-mail","E-mail"))
            ->add('telephone', NumberType::class, $this->getConfig("entrez votre numéro de telephone","Telephone"))
            ->add('society', TextType::class, $this->getConfig("entrez votre societé","Societé"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
