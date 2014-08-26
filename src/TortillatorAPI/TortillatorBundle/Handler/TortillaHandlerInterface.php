<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use TortillatorAPI\TortillatorBundle\Model\TortillaInterface;

interface TortillaHandlerInterface
{
    /**
     * Get a Tortilla given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return TortillaInterface
     */
    public function get($id);

    public function getbybarid($idBar);

    /**
     * Get a list of Tortillas.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 10, $offset = 0);

    /**
     * Post Tortilla, creates a new Tortilla.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return TortillaInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Tortilla.
     *
     * @api
     *
     * @param TortillaInterface   $tortilla
     * @param array           $parameters
     *
     * @return TortillaInterface
     */
    public function put(TortillaInterface $tortilla, array $parameters);

    /**
     * Partially update a Tortilla.
     *
     * @api
     *
     * @param TortillaInterface   $tortilla
     * @param array           $parameters
     *
     * @return TortillaInterface
     */
    public function patch(TortillaInterface $tortilla, array $parameters);
}