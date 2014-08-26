<?php

namespace TortillatorAPI\TortillatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use TortillatorAPI\TortillatorBundle\Model\TortillaInterface;

/**
 * Tortilla
 *
 * @ORM\Table(name="Tortilla")
 * @ORM\Entity(repositoryClass="TortillatorAPI\TortillatorBundle\Entity\TortillaRepository")
 */
class Tortilla implements TortillaInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="average", type="float")
     */
    private $average;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;
    /**
     * @var integer
     *
     * @ORM\Column(name="idBar", type="integer")
     */
    private $idBar;


    public function __construct()
    {

    }


    /**
     * Set id
     *
     * @param integer $id
     * @return Tortilla
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Tortilla
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set average
     *
     * @param float $average
     * @return Tortilla
     */
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * Get average
     *
     * @return float
     */
    public function getAverage()
    {
        return $this->average;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Tortilla
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set idBar
     *
     * @param integer $idBar
     * @return Tortilla
     */
    public function setIdBar($idBar)
    {
        $this->idBar = $idBar;

        return $this;
    }

    /**
     * Get idBar
     *
     * @return integer
     */
    public function getIdBar()
    {
        return $this->idBar;
    }

    public function __toString()
    {
        return strval($this->id);
    }

}
