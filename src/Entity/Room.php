<?php

namespace App\Entity;

use App\Interfaces\EntityWithWarrantyInterface;
use App\Interfaces\EntityWithMaintenanceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * class Room
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @ORM\Table(name="room")
 */
class Room implements EntityWithWarrantyInterface, EntityWithMaintenanceInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\property", inversedBy="rooms")
     */
    private $property;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="room")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Warranties", mappedBy="room")
     */
    private $warranties;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $depth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paint_color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Maintenance", mappedBy="room")
     */
    private $maintenances;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photos", inversedBy="rooms")
     */
    private $photos;

    /**
     * Room constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->warranties = new ArrayCollection();
        $this->maintenances = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Room
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return property|null
     */
    public function getProperty(): ?property
    {
        return $this->property;
    }

    /**
     * @param property|null $property
     * @return Room
     */
    public function setProperty(?property $property): self
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param Item $item
     * @return Room
     */
    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setRoom($this);
        }

        return $this;
    }

    /**
     * @param Item $item
     * @return Room
     */
    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getRoom() === $this) {
                $item->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString(){
        return $this->name;
    }

    /**
     * @return Collection|Warranties[]
     */
    public function getWarranties(): Collection
    {
        return $this->warranties;
    }

    /**
     * @param Warranties $warranty
     * @return Room
     */
    public function addWarranty(Warranties $warranty): self
    {
        if (!$this->warranties->contains($warranty)) {
            $this->warranties[] = $warranty;
            $warranty->setRoom($this);
        }

        return $this;
    }

    /**
     * @param Warranties $warranty
     * @return Room
     */
    public function removeWarranty(Warranties $warranty): self
    {
        if ($this->warranties->contains($warranty)) {
            $this->warranties->removeElement($warranty);
            // set the owning side to null (unless already changed)
            if ($warranty->getRoom() === $this) {
                $warranty->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * @param string|null $width
     * @return Room
     */
    public function setWidth(?string $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @param string|null $height
     * @return Room
     */
    public function setHeight(?string $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDepth(): ?string
    {
        return $this->depth;
    }

    /**
     * @param string|null $depth
     * @return Room
     */
    public function setDepth(?string $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaintColor(): ?string
    {
        return $this->paint_color;
    }

    /**
     * @param string|null $paint_color
     * @return Room
     */
    public function setPaintColor(?string $paint_color): self
    {
        $this->paint_color = $paint_color;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     * @return Room
     */
    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return Collection|Maintenance[]
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    /**
     * @param Maintenance $maintenance
     * @return Room
     */
    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances[] = $maintenance;
            $maintenance->setRoom($this);
        }

        return $this;
    }

    /**
     * @param Maintenance $maintenance
     * @return Room
     */
    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->contains($maintenance)) {
            $this->maintenances->removeElement($maintenance);
            // set the owning side to null (unless already changed)
            if ($maintenance->getRoom() === $this) {
                $maintenance->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return user|null
     */
    public function getUserId(): ?user
    {
        return $this->getProperty()->getUserId();

    }

    /**
     * @return Collection|Photos[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @param Photos $photo
     * @return Room
     */
    public function addPhoto(Photos $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
        }

        return $this;
    }

    /**
     * @param Photos $photo
     * @return Room
     */
    public function removePhoto(Photos $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
        }

        return $this;
    }

}
