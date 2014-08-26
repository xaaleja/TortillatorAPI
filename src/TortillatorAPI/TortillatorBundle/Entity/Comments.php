<?php

namespace TortillatorAPI\TortillatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

use TortillatorAPI\TortillatorBundle\Model\CommentsInterface;

/**
 * Comments
 *
 * @ORM\Table(name="Comments")
 * @ORM\Entity
 */
class Comments implements CommentsInterface
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="idTortilla", type="integer")
     */
    private $idTortilla;

    /**
     * @var string
     * @ORM\Column(name="user", type="string", length=40)
     */
    private $user;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * @var text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    public function __construct()
    {
        $this->datetime = new \DateTime('now');
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Comments
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
     * Set idTortilla
     *
     * @param integer $idTortilla
     * @return Comments
     */
    public function setIdTortilla($idTortilla)
    {
        $this->idTortilla = $idTortilla;

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
     * @return Comments
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Comments
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

    /**
     * Set text
     *
     * @param string $text
     * @return Comments
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


}
