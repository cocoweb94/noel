<?php
// src/Form/Type/CommandeType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                'attr' => ['class' => 'form-control', 'placeholder' => utf8_encode ('Prénom')],
            ])
            ->add('email', EmailType::class, [
                'required'   => true,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email', "pattern" => "^([a-zA-Z0-9_\\-\\.]+)@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.)|(([a-zA-Z0-9\\-]+\\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\\]?)$"],
            ])
            ->add('livraison', ChoiceType::class, [
                'label'=>'Date de retrait',
                'choices'  => [
                    utf8_encode ( '06 décembre 2020' ) => '12-06-2020',
                    utf8_encode ( '13 décembre 2020' ) => '12-13-2020',
                    utf8_encode ( '20 décembre 2020' ) => '12-20-2020',
                    utf8_encode ( '27 décembre 2020' ) => '12-27-2020',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('paiement', ChoiceType::class, [
                'choices'  => [
                    'Carte banquaire' => 'Carte banquaire',
                    utf8_encode ( 'Chèques' ) => 'Cheque',
                ],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}