<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WordRepository")
 */
class Word
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, unique=true)
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WordUsedTimes", mappedBy="word", orphanRemoval=true)
     */
    private $wordUsedTimes;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
        $this->wordUsedTimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Escapes the single word
     *
     * @param string $word
     * @return string
     */
    public static function escapeWord(string $word): string
    {
        $word = mb_ereg_replace("/[^\wА-Яа-я]/", " ", $word);
        $word = trim($word);
        return mb_strtolower($word);
    }

    /**
     * Escapes the array of words
     *
     * @param array $words
     * @return array
     */
    public static function escapeWords(array $words): array
    {
        foreach ($words as &$word) {
            $word = self::escapeWord($word);
        }
        return $words;
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
            $wordUsedTime->setWord($this);
        }

        return $this;
    }

    public function removeWordUsedTime(WordUsedTimes $wordUsedTime): self
    {
        if ($this->wordUsedTimes->contains($wordUsedTime)) {
            $this->wordUsedTimes->removeElement($wordUsedTime);
            // set the owning side to null (unless already changed)
            if ($wordUsedTime->getWord() === $this) {
                $wordUsedTime->setWord(null);
            }
        }

        return $this;
    }
}
