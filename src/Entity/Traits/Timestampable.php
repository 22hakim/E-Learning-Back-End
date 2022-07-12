<?php 

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Timestampable
{
    /**
     * @Assert\DateTime
     * @ORM\Column(name="created_at", type="datetime")
     * @var string A "d-m-Y H:i:s" formatted value
     */
    private \DateTimeInterface $createdAt;

    /**
     * @Assert\DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     * @var string A "d-m-Y H:i:s" formatted value
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }


    public function setCreatedAt(string $createdAt) : Timestampable
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
  
    public function getCreatedAt() : string
    {
        return $this->createdAt;
    }

    
    public function setUpdatedAt(?string $updatedAt) : Timestampable
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

   
    public function getUpdatedAt() : ?string
    {
        return $this->updatedAt;
    }
}