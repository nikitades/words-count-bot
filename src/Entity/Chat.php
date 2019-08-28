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
     * @ORM\Column(type="integer", unique=true)
     */
    private $telegramId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WordUsedTimes", mappedBy="chat", orphanRemoval=true)
     */
    private $wordUsedTimes;

    public function __construct()
    {
        $this->words = new ArrayCollection();
        $this->wordUsedTimes = new ArrayCollection();
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
     * @return Collection|WordUsedTimes[]
     */
    public function getWordUsedTimes(): Collection
    {
        return $this->wordUsedTimes;
    }

    public function addWordUsedTime(WordUsedTimes $wordUsedTime): self
    {
        if (!$this->wordUsedTimes->contains($wordUsedTime)) {
            $this->wordUsedTimes[] = $wordUsedTime;
            $wordUsedTime->setChat($this);
        }

        return $this;
    }

    public function removeWordUsedTime(WordUsedTimes $wordUsedTime): self
    {
        if ($this->wordUsedTimes->contains($wordUsedTime)) {
            $this->wordUsedTimes->removeElement($wordUsedTime);
            // set the owning side to null (unless already changed)
            if ($wordUsedTime->getChat() === $this) {
                $wordUsedTime->setChat(null);
            }
        }

        return $this;
    }
}
