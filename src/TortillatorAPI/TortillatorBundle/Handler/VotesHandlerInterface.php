<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use TortillatorAPI\TortillatorBundle\Model\VotesInterface;

interface VotesHandlerInterface
{
    /**
     * Get a Vote given the identifier
     *
     * @api
     *
     * @param mixed $slug
     *
     * @return VotesInterface
     */
    public function get($slug);

    public function getNumVotes($idTortilla);


    public function getTortillasVotes($id);


    /**
     * Get a list of Votes.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 10, $offset = 0);

    /**
     * Post Vote, creates a new Vote.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return VotesInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Vote.
     *
     * @api
     *
     * @param VotesInterface   $vote
     * @param array           $parameters
     *
     * @return VotesInterface
     */
    public function put(VotesInterface $vote, array $parameters);

    /**
     * Partially update a Vote.
     *
     * @api
     *
     * @param VotesInterface   $vote
     * @param array           $parameters
     *
     * @return VotesInterface
     */
    public function patch(VotesInterface $vote, array $parameters);
}