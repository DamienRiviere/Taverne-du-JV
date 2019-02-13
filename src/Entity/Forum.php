<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Forum
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $platform_title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $platform_logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $platform_style;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $platform_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="forum", orphanRemoval=true)
     */
    private $topics;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }

    /**
     * Permet d'intialiser le slug
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initializeSlug()
    {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPlatformTitle(): ?string
    {
        return $this->platform_title;
    }

    public function setPlatformTitle(string $platform_title): self
    {
        $this->platform_title = $platform_title;

        return $this;
    }

    public function getPlatformLogo(): ?string
    {
        return $this->platform_logo;
    }

    public function setPlatformLogo(?string $platform_logo): self
    {
        $this->platform_logo = $platform_logo;

        return $this;
    }

    public function getPlatformStyle(): ?string
    {
        return $this->platform_style;
    }

    public function setPlatformStyle(?string $platform_style): self
    {
        $this->platform_style = $platform_style;

        return $this;
    }

    public function getPlatformType(): ?string
    {
        return $this->platform_type;
    }

    public function setPlatformType(string $platform_type): self
    {
        $this->platform_type = $platform_type;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setForum($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            // set the owning side to null (unless already changed)
            if ($topic->getForum() === $this) {
                $topic->setForum(null);
            }
        }

        return $this;
    }
}
