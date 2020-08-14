<?php

namespace App\Repository;

use PDO;
use App\Entity\Word;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Word::class);
    }

    /**
     * Saves words if not saved yet
     *
     * @param array $words
     * @return void
     */
    public function ensureWordsIDs(array $words): void
    {
        if (empty($words)) {
            return;
        }
        $em = $this->getEntityManager();
        $query = "INSERT IGNORE INTO word (text) VALUES ";
        $valParams = array_map(function ($wordNumber) {
            return "(:w{$wordNumber})";
        }, array_keys($words));
        $query .= implode(", ", $valParams);
        $params = [];
        foreach ($words as $number => $word) {
            $params["w{$number}"] = $word;
        }
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute($params);
    }
}
