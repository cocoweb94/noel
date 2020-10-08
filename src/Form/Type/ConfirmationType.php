<?php
// src/Form/Type/ContactType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom'],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prenom'],
            ])
            ->add('email', EmailType::class, [
                'required'   => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email', "pattern" => "^([a-zA-Z0-9_\\-\\.]+)@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.)|(([a-zA-Z0-9\\-]+\\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\\]?)$"],
            ])
            ->add('confirme', SubmitType::class, ['label' => 'Confirmer',
                'attr' => ['class' => 'btn btn-block btn-primary text-white py-3 px-5']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}