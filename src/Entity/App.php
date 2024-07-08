<?php

namespace App\Entity;

use App\Repository\AppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: AppRepository::class)]
class App
{

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->userAppRelations = new ArrayCollection();
        $this->pages = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $uid = null;


    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @var Collection<int, UserAppRelation>
     */
    #[ORM\OneToMany(targetEntity: UserAppRelation::class, mappedBy: 'app', orphanRemoval: true)]
    private Collection $userAppRelations;

    /**
     * @var Collection<int, Page>
     */
    #[ORM\OneToMany(targetEntity: Page::class, mappedBy: 'app', orphanRemoval: true)]
    private Collection $pages;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'app', orphanRemoval: true)]
    private Collection $menus;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return Collection<int, UserAppRelation>
     */
    public function getUserAppRelations(): Collection
    {
        return $this->userAppRelations;
    }

    public function addUserAppRelation(UserAppRelation $userAppRelation): static
    {
        if (!$this->userAppRelations->contains($userAppRelation)) {
            $this->userAppRelations->add($userAppRelation);
            $userAppRelation->setApp($this);
        }

        return $this;
    }

    public function removeUserAppRelation(UserAppRelation $userAppRelation): static
    {
        if ($this->userAppRelations->removeElement($userAppRelation)) {
            // set the owning side to null (unless already changed)
            if ($userAppRelation->getApp() === $this) {
                $userAppRelation->setApp(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Page>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): static
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->setApp($this);
        }

        return $this;
    }

    public function removePage(Page $page): static
    {
        if ($this->pages->removeElement($page)) {
            // set the owning side to null (unless already changed)
            if ($page->getApp() === $this) {
                $page->setApp(null);
            }
        }

        return $this;
    }

    public function getUnassignedPagesMenu(): ?Menu
    {
        // return the first menu that is of type unassigned
        foreach ($this->menus as $menu) {
            if ($menu->getType() === 'unassigned') {
                return $menu;
            }
        }

        return null;
    }
    public function getMainMenu(): ?Menu
    {
        // return the first menu that is of type main
        foreach ($this->menus as $menu) {
            if ($menu->getType() === 'main') {
                return $menu;
            }
        }

        return null;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->setApp($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getApp() === $this) {
                $menu->setApp(null);
            }
        }

        return $this;
    }
}
