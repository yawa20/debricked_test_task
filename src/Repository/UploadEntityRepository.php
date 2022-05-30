<?php

namespace App\Repository;

use App\Entity\UploadEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UploadEntity>
 *
 * @method UploadEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadEntity[]    findAll()
 * @method UploadEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UploadEntity::class);
    }

    public function add(UploadEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UploadEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
