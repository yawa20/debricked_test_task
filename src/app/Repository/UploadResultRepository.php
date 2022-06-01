<?php

namespace App\Repository;

use App\Entity\UploadResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UploadResult>
 *
 * @method UploadResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method UploadResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method UploadResult[]    findAll()
 * @method UploadResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UploadResult::class);
    }

    public function add(UploadResult $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UploadResult $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
