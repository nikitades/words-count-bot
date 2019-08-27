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

    /**
     * Increases usage counter for many words for one chat.
     *
     * @param string $text
     * @param integer $chatId
     * @return void
     */
    public function massIncrementUsage(array $words, int $chatId): void
    {
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

    /**
     * Find WordUsetTimes object in array representation.
     * Sadly Doctrine does not support the ORM linking on non-primary keys ATM. (While Eloquent does)
     *
     * @param array $words
     * @param integer $chat_id
     * @return WordUsedTimes[]
     */
    public function findByWordsAndChatId(array $words, int $chat_id): array
    {
        return $this->createQueryBuilder("wc")
                    ->select("wc.usedTimes, wc.wordText")
                    ->where("wc.word IN(:wct)")
                    ->setParameter("wct", $words)
                    ->andWhere("wc.chat = :chatId")
                    ->setParameter("chatId", $chat_id)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Finds best words for one chat id.
     *
     * @param integer $chat_id
     * @return WordUsedTimes[]
     */
    public function findBestByChatId(int $chat_id): array
    {
        return $this->createQueryBuilder("wc")
                        ->select("wc.usedTimes, wc.wordText")
                        ->andWhere("wc.chat = :chatId")
                        ->setParameter("chatId", $chat_id)
                        ->orderBy("wc.usedTimes", "DESC")
                        ->setMaxResults(3)
                        ->getQuery()
                        ->getResult();
    }
}
