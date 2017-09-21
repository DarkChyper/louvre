<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 15/09/2017
 * Time: 19:16
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Locale;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => ' ',
                'required' => true,

            ))
            ->add('fname', TextType::class, array(
                'required' => true,
                'label' => ' ',
            ))
            ->add('country', CountryType::class, array(
                'required' => true,
                'label' => ' ',
                'preferred_choices' => array('FR')
            ))
            ->add('birth', DateTimeType::class, array(
                'label' => ' ',
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'dd/MM/yyyy',
                'invalid_message' => "La date doit être au format JJ/MM/AAAA.",
            ))

            ->add('discount', CheckboxType::class, array(
                'mapped' => false,
                'required' => false,
                'label' => 'Bénéficie d\'un tarif réduit',
                'data' => false, // to uncheck by default

            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(array(
           'data_class' => 'AppBundle\Entity\Ticket',
       ));
    }
}


