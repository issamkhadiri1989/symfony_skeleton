<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Blog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class)
            ->add('content', TextareaType::class, [
                'help' => 'this is an html content.'
            ])
            ->add('draft', CheckboxType::class, [
                'required' => false,
                'data' => $options['is_draft'],
            ])
            ->add('terms', CheckboxType::class, [
                'mapped' => false,
                'label_html' => true,
            ])
            ->add('categories', CollectionType::class, [
                'entry_type' => CategoryItemType::class,
                'allow_add' =>true,
                'allow_delete' => true,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                    'placeholder' => 'Choose a category',
                ],
            ])

            /*->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                // multiple here is because we have a collection of categories
                'multiple' => true,
            ])*/
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            /** @var Blog $data */
            $data = $event->getForm()->getData();
            // isDraft = true or isDraft = null --> we should not publish the current blog.
            if ($data->isDraft() !== true) {
                $data->setPublishDate(new \DateTimeImmutable());
            }
            $event->getForm()->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
            'is_draft' => true,
        ]);
    }
}
