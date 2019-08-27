<?php

namespace App\Repository;

use App\Entity\WordUsedTimes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WordUsedTimes|null find($id, $lockMode = null, $lockVersion = null)
 * @method WordUsedTimes|null findOneBy(array $criteria, array $orderBy = null)
 * @method WordUsedTimes[]    findAll()
 * @method WordUsedTimes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordUsedTimesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WordUsedTimes::class);
    }

    public function massIncrementUsage(string $text, int $chatId): void
    {
        $words = explode(" ", $text);
        $em = $this->getEntityManager();
        
        $query = "INSERT INTO word_used_times (telegram_id, word_text, used_times) VALUES ";
        $values = array_map(function ($number) {
            return "(:c, :w{$number}, 1)";
        }, array_keys($words));
        $query .= implode(", ", $values);
        $query .= " ON DUPLICATE KEY UPDATE used_times = used_times + 1";

        $params = [
            'c' => $chatId
        ];
        foreach ($words as $number => $word) {
            $params["w{$number}"] = $word;
        }
        
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute($params);
    }

    // /**
    //  * @return WordUsedTimes[] Returns an array of WordUsedTimes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WordUsedTimes
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
