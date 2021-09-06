<?php

namespace App\UserInterface\Form;

use App\Infrastructure\Validator\UniqueEmail;
use App\Infrastructure\Validator\UniqueUsername;
use App\UserInterface\DataTransferObject\RegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'email', type: EmailType::class, options: [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new UniqueEmail()
                ],
            ])
            ->add(child: 'username', type: TextType::class, options: [
                'label' => 'Username',
                'constraints' => [
                    new NotBlank(),
                    new UniqueUsername(),
                ],
            ])
            ->add(child: 'plainPassword', type: RepeatedType::class, options: [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                ],
                'invalid_message' => 'Confirmation does not match password!',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Password is too short. It should have {{ limit }} characters or more.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(
            option: 'data_class',
            value: RegistrationDTO::class
        );
    }
}
