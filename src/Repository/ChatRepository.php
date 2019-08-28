<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function ensureChatIsSaved(int $tgChatId, string $chatTitle): void
    {
        $em = $this->getEntityManager();
        $query = "INSERT INTO chat (name, telegram_id) VALUES (:name, :tgid) ON DUPLICATE KEY UPDATE name = :name2";
        $params = [
            'name' => $chatTitle,
            'name2' => $chatTitle,
            'tgid' => $tgChatId
        ];
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute($params);
    }

    public function removeByTgId(int $tgChatId): void
    {
        $this->createQueryBuilder('c')
            ->andWhere('c.telegramId = :tgid')
            ->setParameter('tgid', $tgChatId)
            ->delete();
    }

    public function findByTgId(int $tgChatId): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.telegramId = :tgid')
            ->setParameter('tgid', $tgChatId)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Chat[] Returns an array of Chat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
