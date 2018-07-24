<?php

namespace App\Form;

use App\Entity\Dispatch;
use App\Entity\DispatchStatus;
use App\Entity\SendList;
use App\Entity\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class DispatchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('name_from', TextType::class, [
                'required' => true,
            ])
            ->add('email_from', EmailType::class, [
                'required' => true,
            ])
            ->add('email_cc', TextType::class, [
                'required' => false,
            ])
            ->add('email_bcc', TextType::class, [
                'required' => false,
            ])
            ->add('subject', TextType::class, [
                'required' => true,
            ])
            ->add('send_list', EntityType::class,[
                'class' => SendList::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ])
            ->add('template', EntityType::class,[
                'class' => Template::class,
                'choice_label' => 'title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.title', 'ASC');
                },
            ])
            ->add('status', EntityType::class,[
                'class' => DispatchStatus::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dispatch::class,
        ]);
    }
}
