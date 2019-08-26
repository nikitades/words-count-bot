<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $telegramId;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Word", inversedBy="chats")
     */
    private $words;

    public function __construct()
    {
        $this->words = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTelegramId(): ?int
    {
        return $this->telegramId;
    }

    public function setTelegramId(int $telegramId): self
    {
        $this->telegramId = $telegramId;

        return $this;
    }

    /**
     * @return Collection|Word[]
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWord(Word $word): self
    {
        if (!$this->words->contains($word)) {
            $this->words[] = $word;
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        if ($this->words->contains($word)) {
            $this->words->removeElement($word);
        }

        return $this;
    }
}
