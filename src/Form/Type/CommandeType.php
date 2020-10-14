<?php
// src/Form/Type/CommandeType.php
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

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => utf8_encode ('Nom')],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => utf8_encode ('Pr�nom')],
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => utf8_encode ('Email')],
            ])
            ->add('paiement', ChoiceType::class, [
                'choices'  => [
                    'Carte banquaire' => 'Carte banquaire',
                    utf8_encode ( 'Ch�ques' ) => 'Cheque',
                    utf8_encode ( 'Esp�ces' ) => 'especes',
                ],
                'attr' => ['class' => 'form-control', "id" => "tarifsResa"],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}