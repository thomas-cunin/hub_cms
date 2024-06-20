<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isHidden = null;

    /**
     * @var Collection<int, MenuPage>
     */
    #[ORM\OneToMany(targetEntity: MenuPage::class, mappedBy: 'page', orphanRemoval: true)]
    private Collection $menuPages;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?App $app = null;

    public function __construct()
    {
        $this->menuPages = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function setHidden(bool $isHidden): static
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * @return Collection<int, MenuPage>
     */
    public function getMenuPages(): Collection
    {
        return $this->menuPages;
    }

    public function addMenuPage(MenuPage $menuPage): static
    {
        if (!$this->menuPages->contains($menuPage)) {
            $this->menuPages->add($menuPage);
            $menuPage->setPage($this);
        }

        return $this;
    }

    public function removeMenuPage(MenuPage $menuPage): static
    {
        if ($this->menuPages->removeElement($menuPage)) {
            // set the owning side to null (unless already changed)
            if ($menuPage->getPage() === $this) {
                $menuPage->setPage(null);
            }
        }

        return $this;
    }

    public function getApp(): ?App
    {
        return $this->app;
    }

    public function setApp(?App $app): static
    {
        $this->app = $app;

        return $this;
    }

}
