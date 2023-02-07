<?php

namespace App\Form\Factory\AbstractFactory;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractForm
{
    protected bool $isProcessed;
    protected Request $request;
    protected SessionInterface $session;
    protected ?int $entityIdentifier = null;

    public function __construct(
        protected readonly FormFactoryInterface $factory,
        protected readonly EntityManagerInterface $manager,
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $requestStack->getSession();
    }

    public abstract function buildForm(mixed $data, array $options = []): FormInterface;

    public abstract function getRepository(): ServiceEntityRepository;

    public function processForm(mixed $data, array $options = [], bool $isNewEntry = true): FormInterface
    {
        $form = $this->buildForm($data, $options);
        $form->handleRequest($this->request);

        if ($this->isProcessed = ($form->isSubmitted() && $form->isValid())) {
            $repository = $this->getRepository();
            $repository->saveEntity($data, $isNewEntry);
            if (true === $isNewEntry) {
                $message = \sprintf('A new entry has been added with id `%d`', $data->getId());
            } else {
                $message = \sprintf('The resource `%d` has been updated', $data->getId());
            }
            $this->session->getFlashBag()->add('success', $message);
            $this->entityIdentifier = $data->getId();
        }

        return $form;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    /**
     * @return int|null
     */
    public function getEntityIdentifier(): ?int
    {
        return $this->entityIdentifier;
    }
}