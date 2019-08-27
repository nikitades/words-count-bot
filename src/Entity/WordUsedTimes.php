<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WordUsedTimesRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_used_times_counter", columns={"word_text", "telegram_id"})})
 */
class WordUsedTimes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $usedTimes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat", inversedBy="wordUsedTimes")
     * @ORM\JoinColumn(name="telegram_id", referencedColumnName="telegram_id", nullable=false)
     */
    private $chat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Word", inversedBy="wordUsedTimes")
     * @ORM\JoinColumn(name="word_text", referencedColumnName="text", nullable=false)
     */
    private $word;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $wordText;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsedTimes(): ?int
    {
        return $this->usedTimes;
    }

    public function setUsedTimes(int $usedTimes): self
    {
        $this->usedTimes = $usedTimes;

        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function getWord(): ?Word
    {
        return $this->word;
    }

    public function setWord(?Word $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getWordText(): ?string
    {
        return $this->wordText;
    }

    public function setWordText(string $wordText): self
    {
        $this->wordText = $wordText;

        return $this;
    }
}
