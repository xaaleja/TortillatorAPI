<?php

namespace TortillatorAPI\TortillatorBundle\Model;

Interface BarInterface
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
     * Set name
     *
     * @param string $name
     * @return BarInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set slug
     *
     * @param string $slug
     * @return BarInterface
     */
    public function setSlug($slug);

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug();

    /**
     * Set city
     *
     * @param string $city
     * @return BarInterface
     */
    public function setCity($city);

    /**
     * Get city
     *
     * @return string
     */
    public function getCity();

    /**
     * Set province
     *
     * @param string $province
     * @return BarInterface
     */
    public function setProvince($province);

    /**
     * Get province
     *
     * @return string
     */
    public function getProvince();

    /**
     * Set address
     *
     * @param string $address
     * @return BarInterface
     */
    public function setAddress($address);

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress();

    /**
     * Set schedule
     *
     * @param string $schedule
     * @return BarInterface
     */
    public function setSchedule($schedule);

    /**
     * Get schedule
     *
     * @return string
     */
    public function getSchedule();

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return BarInterface
     */
    public function setLatitude($latitude);

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude();

       /**
     * Set longitude
     *
     * @param float $longitude
     * @return BarInterface
     */
    public function setLongitude($longitude);

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude();

}
