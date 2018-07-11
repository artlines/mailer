<?php

namespace App\Form;

use App\Entity\SendList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\CallbackTransformer;

class SendListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('emails');

        /* $builder->get('emails')->addModelTransformer(new CallbackTransformer(
            function ($emailsAsArray) {
                // transform the array to a string
                return implode('\n', $emailsAsArray);
            },
            function ($emailsAsString) {
                // transform the string back to an array
                return explode('\n', $emailsAsString);
            }
        ));*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SendList::class,
        ]);
    }
}
