<?php

namespace TortillatorAPI\TortillatorBundle\Model;

Interface CommentsInterface
{
    /**
     * Set id
     *
     * @param integer $id
     * @return CommentsInterface
     */
    public function setId($id);

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set idTortilla
     *
     * @param integer $idTortilla
     * @return CommentsInterface
     */
    public function setIdTortilla($idTortilla);

    /**
     * Get idTortilla
     *
     * @return integer
     */
    public function getIdTortilla();

    /**
     * Set user
     *
     * @param string $user
     * @return CommentsInterface
     */
    public function setUser($user);

    /**
     * Get user
     *
     * @return string
     */
    public function getUser();

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return CommentsInterface
     */
    public function setDatetime($datetime);

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime();

    /**
     * Set text
     *
     * @param string $text
     * @return CommentsInterface
     */
    public function setText($text);

    /**
     * Get text
     *
     * @return string
     */
    public function getText();


}
