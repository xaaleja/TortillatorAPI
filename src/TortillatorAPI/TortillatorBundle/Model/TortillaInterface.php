<?php

namespace TortillatorAPI\TortillatorBundle\Model;

Interface TortillaInterface
{
    /**
     * Set id
     *
     * @param integer $id
     * @return BarInterface
     */
    public function setId($id);

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set price
     *
     * @param float $price
     * @return TortillaInterface
     */
    public function setPrice($price);

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice();

    /**
     * Set average
     *
     * @param float $average
     * @return TortillaInterface
     */
    public function setAverage($average);

    /**
     * Get average
     *
     * @return float
     */
    public function getAverage();

    /**
     * Set image
     *
     * @param string $image
     * @return TortillaInterface
     */
    public function setImage($image);

    /**
     * Get image
     *
     * @return string
     */
    public function getImage();

    /**
     * Set idBar
     *
     * @param integer $idBar
     * @return TortillaInterface
     */
    public function setIdBar($idBar);

    /**
     * Get idBar
     *
     * @return integer
     */
    public function getIdBar();
}
