<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * Performs writing data in the database.
     * If it's an insertion than pass true in for $isNewEntry argument and false if it is an update.
     *
     * @param Blog $entity
     * @param bool $isNewEntry
     *
     * @return void
     */
    public function saveEntity(Blog $entity, bool $isNewEntry = false): void
    {
        $manager = $this->getEntityManager();
        if (true === $isNewEntry) {
            $manager->persist($entity);
        }
        $this->doPersistCategories($entity, $manager);
        $manager->flush();
    }

    public function checkUniquenessMethod(array $fields)
    {
        $queryBuilder = $this->createQueryBuilder('b');

        return $queryBuilder->select()
            ->where('b.title', ':title')
            ->orWhere('b.slug', ':slug')
            ->setParameter('title', $fields['title'])
            ->setParameter('slug', $fields['slug'])
            ->getQuery()
            ->getResult();
    }

    /**
     * This function will persist the categories for the given Blog $entity instance.
     *
     * @param Blog                   $entity
     * @param EntityManagerInterface $manager
     *
     * @return void
     */
    private function doPersistCategories(Blog $entity, EntityManagerInterface $manager): void
    {
        foreach ($entity->getCategories() as $item) {
            $item->addBlog($entity);
            $manager->persist($item);
        }
    }
}
