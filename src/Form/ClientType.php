<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('alias', TextType::class, [
                'required' => true,
            ])
            ->add('sender', EmailType::class, [
                'required' => true,
            ])
            ->add('allowIPs', TextAreaType::class, [
                'required' => false,
            ])
            ->add('clientSecret', TextType::class, [
                'required' => false,
                'disabled' => true
            ])
            ->add('isActive', CheckboxType::class, [
                'required' => false,
            ])
//            ->add('createdAt')
//            ->add('updatedAt')
//            ->add('templates')
        ;

        $builder->get('allowIPs')
            ->addModelTransformer(new CallbackTransformer(
                function ($arr) {
                    if ($arr){
                        return implode(',', $arr);
                    }
                },
                function ($str) {
                   return explode(',', $str);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
