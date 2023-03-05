<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: "app:account:show-details", aliases: ["show-details"])]
class OperateUserCommand extends Command
{
    use InitializeCommandStylerTrait;

    public function __construct(private readonly UserRepository $repository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED, 'Username to operate');
        $this->addOption(
            'show-blogs',
            's',
            InputOption::VALUE_NONE,
            "If specified, the blogs associated to this account will be listed"
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $username = $input->getArgument('username');
        if (null === $username) {
            $question = new Question('Please provide a username: ');
            $username = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument('username', $username);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        if (null === $username) {
            $this->styler->error('The username argument is mandatory');

            return self::INVALID;
        }

        /** @var ?User $account */
        $account = $this->repository->findOneBy(['username' => $username]);
        if (null === $account) {
            $this->styler->error(\sprintf(
                'No account with username `%s` was found. The program will exit',
                $username
            ));
        } else {
            $this->doShowAccountDetails($account);
            $showDetails = $input ->getOption('show-blogs');
            if (true === $showDetails) {
                $this->doRenderBlogs($output, $account);
            }
        }

        return self::SUCCESS;
    }

    /**
     * Renders the blogs list of the given account in a table.
     *
     * @param OutputInterface $output
     * @param User            $account
     *
     * @return void
     */
    private function doRenderBlogs(OutputInterface $output, User $account): void
    {
        $this->styler->writeln('The following are the blogs associated to this account');
        $table = new Table($output);
        $table->setHeaders(['ID', 'TITLE', 'PUBLISHED', 'PUBLISHED AT']);

        foreach ($account->getBlogs() as $blog) {
            $table->addRow([
                $blog->getId(),
                $blog->getTitle(),
                $blog->isDraft() ? "NO" : "YES",
                $blog->getPublishDate()->format('F d, Y'),
            ]);
        }

        $table->render();
    }

    /**
     * Renders main details of the given account.
     *
     * @param User $account
     *
     * @return void
     */
    private function doShowAccountDetails(User $account): void
    {
        $this->styler->title(\sprintf('User found : %s', $account->getFullName()));
        $this->styler->listing([
            "Account state: " . ($account->isDisabled() ? "Disabled" : "Active"),
            "Account verified: " . ($account->isVerified() ? "Yes" : "No"),
            "Last connection date: " . ($account->getLastConnectionDate()->format('F d, Y')),
            "Roles: " . (\implode(', ', $account->getRoles()))
        ]);
    }
}