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
                'label'=> utf8_encode ('Prénom'),
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => utf8_encode ('Prénom')],
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email', "pattern" => "^([a-zA-Z0-9_\\-\\.]+)@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.)|(([a-zA-Z0-9\\-]+\\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\\]?)$"],
            ])
            ->add('tel', TextType::class, [
                'label'=> utf8_encode ('Téléphone'),
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => utf8_encode ('Téléphone')],
            ])
            ->add('livraison', ChoiceType::class, [
                'label'=>'Votre choix pour le retrait des commandes (sortie des cultes)',
                'choices'  => [
                    utf8_encode ( 'dimanche 29 novembre' ) => '11-29-2020',
                    utf8_encode ( 'dimanche 6 décembre' ) => '12-06-2020',
                    utf8_encode ( 'samedi 12 décembre' ) => '12-12-2020',
                    utf8_encode ( 'dimanche 13 décembre' ) => '12-13-2020',
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