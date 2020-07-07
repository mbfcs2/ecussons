<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Destination", inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private $destination;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fabricant", inversedBy="items")
     */
    private $fabricant;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reference_fabricant;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $designation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Illustration", mappedBy="item")
     */
    private $illustrations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="items")
     */
    private $users;

    public function __construct()
    {
        $this->illustrations = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDestination(): ?Destination
    {
        return $this->destination;
    }

    public function setDestination(?Destination $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getFabricant(): ?Fabricant
    {
        return $this->fabricant;
    }

    public function setFabricant(?Fabricant $fabricant): self
    {
        $this->fabricant = $fabricant;

        return $this;
    }

    public function getReferenceFabricant(): ?string
    {
        return $this->reference_fabricant;
    }

    public function setReferenceFabricant(?string $reference_fabricant): self
    {
        $this->reference_fabricant = $reference_fabricant;

        return $this;
    }

	public function __toString() {
               		return "Item ".$this->id." (".$this->destination." - ".$this->designation.")";
               	}

    

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

	

    /**
     * @return Collection|Illustration[]
     */
    public function getIllustrations(): Collection
    {
        return $this->illustrations;
    }
	
	public function getMainIllustration(): ?Illustration
    {
        foreach ($this->illustrations as $illu)
		{
			if ($illu->getIsMain())
				return $illu ;
		}
		return null ;
    }

    public function addIllustration(Illustration $illustration): self
    {
        if (!$this->illustrations->contains($illustration)) {
            $this->illustrations[] = $illustration;
            $illustration->setItem($this);
        }

        return $this;
    }

    public function removeIllustration(Illustration $illustration): self
    {
        if ($this->illustrations->contains($illustration)) {
            $this->illustrations->removeElement($illustration);
            // set the owning side to null (unless already changed)
            if ($illustration->getItem() === $this) {
                $illustration->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addItem($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeItem($this);
        }

        return $this;
    }


}
