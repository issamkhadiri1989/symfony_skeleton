<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * Delete all entries.
     *
     * @param iterable $entries
     * @param bool     $doFlush
     *
     * @return void
     */
    public function removeEntries(iterable $entries, bool $doFlush = false): void
    {
        if (empty($entries) === true) {
            return;
        }

        foreach ($entries as $entry) {
            $this->remove($entry);
        }

        if (true === $doFlush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Remove a single entity.
     *
     * @param mixed $entry
     * @param bool  $doFlush
     *
     * @return void
     */
    public function remove(object $entry, bool $doFlush = false): void
    {
        $manager = $this->getEntityManager();
        $manager->remove($entry);

        if (true === $doFlush) {
            $manager->flush();
        }
    }
}
