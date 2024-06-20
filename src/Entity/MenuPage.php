<?php

namespace App\Entity;

use App\Repository\MenuPageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuPageRepository::class)]
class MenuPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'menuPages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PageMenu $pageMenu = null;

    #[ORM\ManyToOne(inversedBy: 'menuPages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Page $page = null;

    #[ORM\Column]
    private ?int $pagePosition = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageMenu(): ?PageMenu
    {
        return $this->pageMenu;
    }

    public function setPageMenu(?PageMenu $pageMenu): static
    {
        $this->pageMenu = $pageMenu;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getPagePosition(): ?int
    {
        return $this->pagePosition;
    }

    public function setPagePosition(int $pagePosition): static
    {
        $this->pagePosition = $pagePosition;

        return $this;
    }

}
