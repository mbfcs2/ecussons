<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
 class Illustration
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
		* @ORM\Column(type="string", length=255)
		* @var string
		*/
	   private $image;
   
	   /**
		* @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
		* @var File
		*/
	   private $imageFile;
   
	   /**
		* @ORM\Column(type="datetime")
		* @var \DateTime
		*/
	   private $updatedAt;

	   /**
		* @ORM\ManyToOne(targetEntity="App\Entity\item", inversedBy="illustrations")
		*/
	   private $item;

	   /**
		* @ORM\Column(type="string", length=100, nullable=true)
		*/
	   private $description;

	   /**
		* @ORM\Column(type="boolean")
		*/
	   private $is_main;
   
	   // ...
   
	public function __construct()
	   {
		   $this->is_main = true ;
	   }
   
	public function getId(): ?int
	   {
		   return $this->id;
	   }
   
	   public function setImageFile(File $image = null)
	   {
		   $this->imageFile = $image;
   
		   // VERY IMPORTANT:
		   // It is required that at least one field changes if you are using Doctrine,
		   // otherwise the event listeners won't be called and the file is lost
		   if ($image) {
			   // if 'updatedAt' is not defined in your entity, use another property
			   $this->updatedAt = new \DateTime('now');
		   }
	   }
   
	   public function getImageFile()
	   {
		   return $this->imageFile;
	   }
   
	   public function setImage($image)
	   {
		   $this->image = $image;
	   }
   
	   public function getImage()
	   {
		   return $this->image;
	   }

	   public function getItem(): ?item
	   {
		   return $this->item;
	   }

	   public function setItem(?item $item): self
	   {
		   $this->item = $item;
			// Todo :
			// Ne pas autoriser le fait de setter l'item
			// si on a déjà une illustration is_main pour cet item

		   return $this;
	   }
			
	public function __toString() {
		return $this->description. "(id ".$this->id.")";
	}

	 public function getDescription(): ?string
	 {
		 return $this->description;
	 }

	 public function setDescription(?string $description): self
	 {
		 $this->description = $description;

		 return $this;
	 }

	 public function getIsMain(): ?bool
	 {
		 return $this->is_main;
	 }

	 public function setIsMain(bool $is_main): self
	 {
		 $this->is_main = $is_main;
			// Todo :
			// Ne pas autoriser le fait de setter is_main = true
			// si on a déjà une illustration is_main pour le même item
		 return $this;
	 }
			
}