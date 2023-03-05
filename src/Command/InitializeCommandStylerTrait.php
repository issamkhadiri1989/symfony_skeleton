<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * The purpose of this trait is only centralize the initialize method because it is common to all commands.
 */
trait InitializeCommandStylerTrait
{
    private SymfonyStyle $styler;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->styler = new SymfonyStyle($input, $output);
    }
}
