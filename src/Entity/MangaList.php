<?php

namespace App\Entity;

use App\Repository\MangaListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MangaListRepository::class)
 */
class MangaList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $episode_num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $episode_url;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

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

    public function getEpisodeNum(): ?int
    {
        return $this->episode_num;
    }

    public function setEpisodeNum(?int $episode_num): self
    {
        $this->episode_num = $episode_num;

        return $this;
    }

    public function getEpisodeUrl(): ?string
    {
        return $this->episode_url;
    }

    public function setEpisodeUrl(?string $episode_url): self
    {
        $this->episode_url = $episode_url;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
