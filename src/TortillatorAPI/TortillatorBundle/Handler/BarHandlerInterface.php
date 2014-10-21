<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use TortillatorAPI\TortillatorBundle\Model\BarInterface;

interface BarHandlerInterface
{
    /**
     * Get a Bar given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return BarInterface
     */
    public function get($id);

    public function getByLatLong($lat, $long);
    public function getRecommendationsByLatLong($username, $lat, $long);
    public function getVotesByLatLong($username, $lat, $long);



    public function getBarBySlug($slug);


    /**
     * Get a list of Bars.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 10, $offset = 0);

    /**
     * Post Bar, creates a new Bar.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return BarInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Bar.
     *
     * @api
     *
     * @param BarInterface   $bar
     * @param array           $parameters
     *
     * @return BarInterface
     */
    public function put(BarInterface $bar, array $parameters);

    /**
     * Partially update a Bar.
     *
     * @api
     *
     * @param BarInterface   $bar
     * @param array           $parameters
     *
     * @return BarInterface
     */
    public function patch(BarInterface $bar, array $parameters);
}