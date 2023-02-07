<?php

declare(strict_types=1);

namespace App\Form\Factory;

use App\Form\Factory\AbstractFactory\AbstractForm;
use Symfony\Component\HttpFoundation\Response;

interface FactoryInterface
{
    public function createForm(mixed $data, bool $isNewEntry, array $options = []): AbstractForm;

    public function createResponse(): Response;
}
