<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModerationRepository")
 */
class Moderation
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
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentArticle", mappedBy="moderation", orphanRemoval=true)
     */
    private $commentArticle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommentTopic", mappedBy="moderation", orphanRemoval=true)
     */
    private $commentTopics;

    public function __construct()
    {
        $this->commentArticle = new ArrayCollection();
        $this->commentTopics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|CommentArticle[]
     */
    public function getCommentArticle(): Collection
    {
        return $this->commentArticle;
    }

    public function addCommentArticle(CommentArticle $commentArticle): self
    {
        if (!$this->commentArticle->contains($commentArticle)) {
            $this->commentArticle[] = $commentArticle;
            $commentArticle->setModeration($this);
        }

        return $this;
    }

    public function removeCommentArticle(CommentArticle $commentArticle): self
    {
        if ($this->commentArticle->contains($commentArticle)) {
            $this->commentArticle->removeElement($commentArticle);
            // set the owning side to null (unless already changed)
            if ($commentArticle->getModeration() === $this) {
                $commentArticle->setModeration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentTopic[]
     */
    public function getCommentTopics(): Collection
    {
        return $this->commentTopics;
    }

    public function addCommentTopic(CommentTopic $commentTopic): self
    {
        if (!$this->commentTopics->contains($commentTopic)) {
            $this->commentTopics[] = $commentTopic;
            $commentTopic->setModeration($this);
        }

        return $this;
    }

    public function removeCommentTopic(CommentTopic $commentTopic): self
    {
        if ($this->commentTopics->contains($commentTopic)) {
            $this->commentTopics->removeElement($commentTopic);
            // set the owning side to null (unless already changed)
            if ($commentTopic->getModeration() === $this) {
                $commentTopic->setModeration(null);
            }
        }

        return $this;
    }
}
