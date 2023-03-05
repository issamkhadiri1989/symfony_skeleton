<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Account;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: "app:delete:inactive-accounts")]
class DeleteInactiveAccountsCommand extends Command
{
    use InitializeCommandStylerTrait;

    public function __construct(private readonly Account $account)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'days',
            'd',
            InputOption::VALUE_REQUIRED,
            'Amount of days limit  to decide that the account is inactive',
            Account::DEFAULT_AMOUNT_DAYS
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (\is_numeric($n = $input->getOption('days')) === false || (int) $n < 0) {
            $this->styler->error('Please provide a correct amount of days');

            return self::INVALID;
        }

        $deletedAccounts = $this->account->removeAccountsSince((int) $n, true);

        if ($deletedAccounts === 0) {
            $this->styler->info('No account to delete');
        } else {
            $this->styler->text(\sprintf('<info>%d</info> accounts have been deleted.', $deletedAccounts));
        }

        return Command::SUCCESS;
    }
}
