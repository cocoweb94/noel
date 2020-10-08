<?php
// src/Form/Type/ReservationType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime('now');
        $builder
            ->add('services', ChoiceType::class, [
                'choices'  => [
                    'Business' => 'b',
                    'Prestige' => 'p',
                    'Van Luxe' => 'v',
                ],
                'attr' => ['class' => 'form-control', "id" => "servicesResa"],
            ])
            ->add('tarifs', ChoiceType::class, [
                'choices'  => [
                    'Paris Intramuros' => 'i',
                    utf8_encode ( 'Transfert Aéroports' ) => 'a',
                    'Transfert Gares' => 'g',
                    utf8_encode ('Mise à disposition') => 'm',
                    'Hors Ile de France' => 'r',
                    'Tourisme / Shopping' => 't',
                    utf8_encode ('Evévements') => 'e',
                ],
                'attr' => ['class' => 'form-control', "id" => "tarifsResa"],
            ])
            ->add('formule', ChoiceType::class, [
                'choices'  => [
                    'Paris - Paris 30 EUR' => 'pp',
                    'Paris - Banlieue (petite couronne) 35 EUR' => 'pbp',
                    'Paris - Banlieue (grande couronne) 40 EUR' => 'pbg',
                    'Banlieue - Banlieue 45 EUR' => 'pbb',
                    'Paris - Orly 45 EUR' => 'ol',
                    'Paris - CDG 60 EUR' => 'cdg',
                    'Paris - Beauvais 120 EUR' => 'bea',
                    'Paris - Gares 30 EUR' => 'pg',
                    'Paris - Marne la vall&eacute;e 60 EUR' => 'pm',
                    'Mise &agrave; disposition (heure) 45 EUR' => 'misap',
                    'Toutes demandes Sur devis' => 'td',
                    'Tout type evenements Sur devis' => 'eve',
                    'Toutes demandes Sur devis' => 'prp',
                ],
                'attr' => ['class' => 'form-control', "id" => "formuleResa"],
            ])
            ->add('depart', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => utf8_encode ('Adresse de départ')],
            ])
            ->add('destination', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse de destination'],
            ])
            ->add('date', DateType::class, array(
                'required' => true,
                'widget' => 'single_text',
                'label' => utf8_encode ('Date de Départ'),
                'attr' => [
                    'class' => 'form-control input-inline',
                    'data-format' => 'dd/MM/yyyy',
                    'min' => $date->format('Y-m-d')
                ],
            ))
            ->add('heure', TimeType::class, array(
                'required' => true,
                'widget' => 'single_text',
                'label' => utf8_encode ('Heure de Départ'),
                'attr' => [
                    'class' => 'form-control input-inline',
                    'data-format' => 'hh:mm',
                ],
            ))
            ->add('reserve', SubmitType::class, ['label' => 'Reserver',
                'attr' => ['class' => 'btn btn-block btn-primary text-white py-3 px-5']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}