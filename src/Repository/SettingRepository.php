<?php

namespace App\Repository;

use App\Entity\Setting;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository
{

    /**
     * @var QueryBuilder
     */
    protected $q;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function setOrCreate(string $name, string $value): ?Setting
    {
        $existingSetting = $this->createQueryBuilder('s')
                                ->andWhere('s.name = :val')
                                ->setParameter('val', $name)
                                ->getQuery()
                                ->getSingleResult();
        if ($existingSetting) {
            $existingSetting->setValue($value);
            $this->_em->persist($existingSetting);
            $this->_em->flush();
            return $existingSetting;
        }
        $newSetting = new Setting($name, $value);
        $this->_em->persist($newSetting);
        $this->_em->flush();
        return $newSetting;
    }

    // /**
    //  * @return Setting[] Returns an array of Setting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Setting
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
