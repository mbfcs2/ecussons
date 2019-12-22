<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Destinations
 *
 * @ORM\Table(name="destinations", indexes={@ORM\Index(name="dept_idx", columns={"parent_id"}), @ORM\Index(name="namedest", columns={"name"})})
 * @ORM\Entity
 */
class Destination
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="insee_code", type="string", length=5, nullable=true)
     */
    private $inseeCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="zip_code", type="string", length=5, nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var float|null
     *
     * @ORM\Column(name="gps_lat", type="float", precision=16, scale=14, nullable=true)
     */
    private $gpsLat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="gps_lng", type="float", precision=17, scale=14, nullable=true)
     */
    private $gpsLng;

    /**
     * @var \Destination
     *
     * @ORM\ManyToOne(targetEntity="Destination", inversedBy="destinations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="destination")
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DisplayType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $displayType;


    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInseeCode(): ?string
    {
        return $this->inseeCode;
    }

    public function setInseeCode(?string $inseeCode): self
    {
        $this->inseeCode = $inseeCode;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getGpsLat(): ?float
    {
        return $this->gpsLat;
    }

    public function setGpsLat(?float $gpsLat): self
    {
        $this->gpsLat = $gpsLat;

        return $this;
    }

    public function getGpsLng(): ?float
    {
        return $this->gpsLng;
    }

    public function setGpsLng(?float $gpsLng): self
    {
        $this->gpsLng = $gpsLng;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
	
	public function __toString() {
                           		$toreturn = $this->name ;
                           		if (!is_null($this->zipCode))
                           			$toreturn = $toreturn." (".($this->zipCode).")" ;
                           		return $toreturn;
                           	}
	
	public function affiche_arbo() {
                           		$toreturn = $this->name ;
                           		if (!is_null($this->parent))
                           			$toreturn = ($this->parent->affiche_arbo())." > ".$this->name ;
                           		return $toreturn;
                           	}

    public function getArbo() {
        $arbo = [];
        $destination_tmp = $this;
        while(!is_null($destination_tmp)) {
            array_unshift($arbo, $destination_tmp);
            $destination_tmp = $destination_tmp->getParent();
        }
        return $arbo;
    }
	

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setDestination($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getDestination() === $this) {
                $item->setDestination(null);
            }
        }

        return $this;
    }

    public function getDisplayType(): ?displayType
    {
        return $this->displayType;
    }

    public function setDisplayType(?displayType $displayType): self
    {
        $this->displayType = $displayType;

        return $this;
    }


}
