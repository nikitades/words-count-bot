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
     * Increases usage counter for many words for one chat
     *
     * @param string $text
     * @param integer $chatId
     * @return void
     */
    public function massIncrementUsage(array $words, int $chatId): void
    {
        $em = $this->getEntityManager();

        $wordsInQuery = array_map(function ($number) {
            return ":w{$number}";
        }, array_keys($words));

        $query = "INSERT INTO word_used_times (used_times, chat_id, word_id) 
                    SELECT 1, c.id, w.id 
                        FROM word w
                        LEFT JOIN chat c ON c.telegram_id = :tgid
                        WHERE w.text IN(" . implode(", ", $wordsInQuery) . ")
                    ON DUPLICATE KEY UPDATE used_times = used_times + 1";
        $params = [
            'tgid' => $chatId
        ];
        foreach ($words as $number => $word) {
            $params["w{$number}"] = $word;
        }
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute($params);
    }

    /**
     * Finds array of WordUsetTimes 
     *
     * @param array $words
     * @param integer $chat_id
     * @return WordUsedTimes[]
     */
    public function findByWordsAndChatId(array $words, int $chat_id): array
    {
        return $this->getEntityManager()
            ->createQuery("
                            SELECT wu, word, chat
                            FROM App\Entity\WordUsedTimes wu
                            LEFT JOIN wu.word word
                            LEFT JOIN wu.chat chat
                            WHERE word.text IN(:wt)
                            AND chat.telegramId = :tgid
                        ")
            ->setParameter('wt', $words)
            ->setParameter('tgid', $chat_id)
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
        return $this->getEntityManager()
            ->createQuery("
                            SELECT wu, word, chat
                            FROM App\Entity\WordUsedTimes wu
                            LEFT JOIN wu.word word
                            LEFT JOIN wu.chat chat
                            WHERE chat.telegramId = :tgid
                            ORDER BY wu.usedTimes DESC
                        ")
            ->setMaxResults(3)
            ->setParameter('tgid', $chat_id)
            ->getResult();
    }
}
