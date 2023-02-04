<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\Transformer\SlugToCategoryTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryItemType extends AbstractType
{
    private SlugToCategoryTransformer $transformer;

    public function __construct(SlugToCategoryTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['placeholder' => 'Specify the category\'s slug']
        ]);
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }
}
