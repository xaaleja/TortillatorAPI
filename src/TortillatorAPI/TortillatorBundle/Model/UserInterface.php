<?php

namespace TortillatorAPI\TortillatorBundle\Model;

Interface UserInterface
{
    /**
     * Set username
     *
     * @param string $username
     * @return UserInterface
     */
    public function setUsername($username);

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername();

    /**
     * Set password
     *
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password);

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Set email
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set city
     *
     * @param string $city
     * @return UserInterface
     */
    public function setCity($city);

    /**
     * Get city
     *
     * @return string
     */
    public function getCity();


}
