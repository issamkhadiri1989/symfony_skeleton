<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use App\Validator\PasswordConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    /**
     * The ProfileType has the same full name field, so no need to re add the same fields twice.
     *
     * @return string
     */
    public function getParent(): string
    {
        return ProfileType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->remove('shippingAddress');
        $builder
            ->add('username', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Username']])
            ->add('registerPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Password'],
                    'constraints' => [new PasswordConstraint()],
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => ['placeholder' => 'Confirm your password'],
                    'constraints' => [new PasswordConstraint()],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => false,
            'data_class' => User::class,
            'validation_groups' => ['registration', 'general_info'],
        ]);
    }
}
