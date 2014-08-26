<?php

namespace TortillatorAPI\TortillatorBundle\Model;

Interface VotesInterface
{
    /**
     * Set idTortilla
     *
     * @param integer $idTortilla
     * @return VotesInterface
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
     * @return VotesInterface
     */
    public function setUser($user);

    /**
     * Get user
     *
     * @return string
     */
    public function getUser();

    /**
     * Set slug
     *
     * @param string $slug
     * @return VotesInterface
     */
    public function setSlug($slug);

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug();

    /**
     * Set rating
     *
     * @param integer $rating
     * @return VotesInterface
     */
    public function setRating($rating);

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating();

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return VotesInterface
     */
    public function setDatetime($datetime);

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime();

}
