<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use TortillatorAPI\TortillatorBundle\Model\CommentsInterface;

interface CommentsHandlerInterface
{
    /**
     * Get a Comment given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return CommentsInterface
     */
    public function get($id);

    /**
     * Get a list of Comments.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 10, $offset = 0);

    /**
     * Post Commen, creates a new Comment.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return CommentsInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Comment.
     *
     * @api
     *
     * @param CommentsInterface   $comment
     * @param array           $parameters
     *
     * @return CommentsInterface
     */
    public function put(CommentsInterface $comment, array $parameters);

    /**
     * Partially update a Comment.
     *
     * @api
     *
     * @param CommentsInterface   $comment
     * @param array           $parameters
     *
     * @return CommentsInterface
     */
    public function patch(CommentsInterface $comment, array $parameters);
}