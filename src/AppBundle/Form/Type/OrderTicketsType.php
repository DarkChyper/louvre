<?php
/**
 * Created by PhpStorm.
 * User: darkchyper
 * Date: 21/09/2017
 * Time: 19:45
 */

namespace AppBundle\Form\Type;


use AppBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderTicketsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tickets', CollectionType::class, array(
            'entry_type' => TicketType::class,
            'entry_options' => array(
                'label' => false
            ),
    ))
            ->add('suivant', SubmitType::class );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => Order::class,
        ));
    }
}


