<?php

namespace App\Form\Factory;

use App\Entity\Blog;
use App\Form\Factory\AbstractFactory\AbstractForm;
use App\Form\Type\BlogType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Form\FormInterface;

class BlogForm extends AbstractForm
{
    public function buildForm(mixed $data, array $options = []): FormInterface
    {
        return $this->factory->create(BlogType::class, $data, $options);
    }

    public function getRepository(): ServiceEntityRepository
    {
        return $this->manager->getRepository(Blog::class);
    }
}