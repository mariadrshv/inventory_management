<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotosRepository")
 */
class Photos
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
    private $path;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Property", mappedBy="photos")
     */
    private $properties;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Room", mappedBy="photos")
     */
    private $rooms;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", mappedBy="photos")
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Warranties", mappedBy="photos")
     */
    private $warranties;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Technician", mappedBy="photo", cascade={"persist", "remove"})
     */
    private $technician;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Maintenance", mappedBy="photos")
     */
    private $maintenances;

    /**
     * Photos constructor.
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
        $this->rooms = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->warranties = new ArrayCollection();
        $this->maintenances = new ArrayCollection();
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
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Photos
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Collection|Property[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    /**
     * @param Property $property
     * @return Photos
     */
    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->addPhoto($this);
        }

        return $this;
    }

    /**
     * @param Property $property
     * @return Photos
     */
    public function removeProperty(Property $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            $property->removePhoto($this);
        }

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    /**
     * @param Room $room
     * @return Photos
     */
    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->addPhoto($this);
        }

        return $this;
    }

    /**
     * @param Room $room
     * @return Photos
     */
    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
            $room->removePhoto($this);
        }

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
     * @return Photos
     */
    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addPhoto($this);
        }

        return $this;
    }

    /**
     * @param Item $item
     * @return Photos
     */
    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->removePhoto($this);
        }

        return $this;
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
     * @return Photos
     */
    public function addWarranty(Warranties $warranty): self
    {
        if (!$this->warranties->contains($warranty)) {
            $this->warranties[] = $warranty;
            $warranty->addPhoto($this);
        }

        return $this;
    }

    /**
     * @param Warranties $warranty
     * @return Photos
     */
    public function removeWarranty(Warranties $warranty): self
    {
        if ($this->warranties->contains($warranty)) {
            $this->warranties->removeElement($warranty);
            $warranty->removePhoto($this);
        }

        return $this;
    }

    /**
     * @return Technician|null
     */
    public function getTechnician(): ?Technician
    {
        return $this->technician;
    }

    /**
     * @param Technician|null $technician
     * @return Photos
     */
    public function setTechnician(?Technician $technician): self
    {
        $this->technician = $technician;

        // set (or unset) the owning side of the relation if necessary
        $newPhoto = $technician === null ? null : $this;
        if ($newPhoto !== $technician->getPhoto()) {
            $technician->setPhoto($newPhoto);
        }

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
     * @return Photos
     */
    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances[] = $maintenance;
            $maintenance->addPhoto($this);
        }

        return $this;
    }

    /**
     * @param Maintenance $maintenance
     * @return Photos
     */
    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->contains($maintenance)) {
            $this->maintenances->removeElement($maintenance);
            $maintenance->removePhoto($this);
        }

        return $this;
    }
}
