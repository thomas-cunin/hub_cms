<?php

namespace App\Entity;

use App\Repository\PageMenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PageMenuRepository::class)]
class PageMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Length(
        max: 42,
        maxMessage: 'Name cannot be longer than {{ limit }} characters'
    )]
    private ?string $name = null;

    /**
     * @var Collection<int, MenuPage>
     */
    #[ORM\OneToMany(targetEntity: MenuPage::class, mappedBy: 'pageMenu', orphanRemoval: true)]
    private Collection $menuPages;

    #[ORM\ManyToOne(inversedBy: 'pageMenus')]
    private ?App $app = null;

    #[ORM\Column(length: 255)]
    private ?string $uid = null;

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
            $menuPage->setPageMenu($this);
        }

        return $this;
    }

    public function removeMenuPage(MenuPage $menuPage): static
    {
        if ($this->menuPages->removeElement($menuPage)) {
            // set the owning side to null (unless already changed)
            if ($menuPage->getPageMenu() === $this) {
                $menuPage->setPageMenu(null);
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

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

}
