<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TicketType extends AbstractType
{   
    private function getConfig($placeholder,$label)
    {
        return [

            'attr' => 
            [
                'placeholder' => $placeholder
            ],
            'label' => $label 
        ];  
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('author' ,EntityType::class, $this->getConfig("entrez votre email","E-mail"))
            ->add('object',TextType::class, $this->getConfig("","Objet"))
            ->add('subject',TextareaType::class, $this->getConfig("Entrez votre preoccupation","Preoccupation"))
            //->add('date', DateType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
