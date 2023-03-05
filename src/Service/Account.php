<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Blog\BlogManager;

class Account
{
    public const DEFAULT_AMOUNT_DAYS = 30;

    public function __construct(
        private readonly UserRepository $repository,
        private readonly BlogManager $blogManager
    ) {
    }

    /**
     * Removes all account that are not active for more than the given number of days.
     *
     * @param int  $days
     * @param bool $flush
     *
     * @return int
     */
    public function removeAccountsSince(int $days, bool $flush = false): int
    {
        $verifiedAccountsToDelete = $this->repository->findOldVerifiedAccounts($days);
        $totalAccountsToDelete = \count($verifiedAccountsToDelete);
        // perform effective deletion
        $this->performAccountsDeletion($verifiedAccountsToDelete, $flush);

        //@todo perhaps send an email to the admin (for example dispatch an event ?)

        return $totalAccountsToDelete;
    }

    /**
     * Perform deletion of the given account internally.
     *
     * @param array $accounts
     * @param bool  $flush
     *
     * @return void
     */
    private function performAccountsDeletion(array $accounts, bool $flush = false): void
    {
        // delete blogs of all accounts first
        \array_walk($accounts, function (User $account) {
            $this->blogManager->removeRelatedBlogs($account);
        });
        $this->repository->removeEntries($accounts, $flush);
    }
}
