<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 15/09/2017
 * Time: 19:16
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitDate', DateTimeType::class, array(
                'label' => ' ',
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'dd/MM/yyyy',
                'invalid_message' => "La date doit être au format JJ/MM/AAAA.",
            ))
            ->add('ticketType', ChoiceType::class, array(
                'required' => true,
                'label' => ' ',
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    "Journée complète" => "FULL",
                    "Demi Journée (à partir de 14h)" => "HALF"
                )
            ))
            ->add('ticketNumber', ChoiceType::class, array(
                'required' => true,
                'label' => ' ',
                'multiple' => false,
                'choices' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8,
                    9 => 9,
                    10 => 10,
                )
            ))
            ->add('mailContact', EmailType::class, array(
                'required' => true,
                'label' => " ",

            ))
            ->add('suivant',      SubmitType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(array(
           'data_class' => 'AppBundle\Entity\Order',
       ));
    }
}


