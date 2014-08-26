<?php

namespace TortillatorAPI\TortillatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use TortillatorAPI\TortillatorBundle\Model\VotesInterface;

/**
 * Votes
 *
 * @ORM\Table(name="Votes")
 * @ORM\Entity(repositoryClass="TortillatorAPI\TortillatorBundle\Entity\VotesRepository")
 */
class Votes implements VotesInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="idTortilla", type="integer")
     */
    private $idTortilla;

    /**
     * @ORM\Id
     * @ORM\Column(name="user", type="string", length=40)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=50)
     */
    private $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /*public function __construct($user, $idTortilla)
    {
        $this->user = $user;
        $this->idTortilla = $idTortilla;
    }*/
    public function __construct()
    {
        $this->datetime = new \DateTime('now');
        $this->slug = $this->idTortilla . '-' . $this->user;
    }

    /**
     * Set idTortilla
     *
     * @param integer $idTortilla
     * @return Votes
     */
    public function setIdTortilla($idTortilla)
    {
        $this->idTortilla = $idTortilla;
        $slug = $this->idTortilla . "-" . $this->user;
        $this->setSlug($slug);

        return $this;
    }

    /**
     * Get idTortilla
     *
     * @return integer
     */
    public function getIdTortilla()
    {
        return $this->idTortilla;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Votes
     */
    public function setUser($user)
    {
        $this->user = $user;
        $slug = $this->idTortilla . "-" . $this->user;
        $this->setSlug($slug);

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set slug
     *
     * @param string
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return Votes
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Votes
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }
}
