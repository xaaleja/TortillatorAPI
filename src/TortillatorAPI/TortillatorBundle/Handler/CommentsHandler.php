<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use TortillatorAPI\TortillatorBundle\Model\CommentsInterface;
use TortillatorAPI\TortillatorBundle\Form\CommentsType;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class CommentsHandler implements CommentsHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Comment.
     *
     * @param mixed $id
     *
     * @return CommentsInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    public function getCommentsByTortilla($id)
    {
        return $this->repository->findBy(array('idTortilla' => $id), array('datetime' => 'DESC'), null, null);
    }

    /**
     * Create a new Comment.
     *
     * @param array $parameters
     *
     * @return CommentsInterface
     */
    public function post(array $parameters)
    {
        $comment = $this->createComment();

        return $this->processForm($comment, $parameters, 'POST');
    }

    private function createComment()
    {
         return new $this->entityClass();
    }

    /**
     * Processes the form.
     *
     * @param CommentsInterface $comment
     * @param array         $parameters
     * @param String        $method
     *
     * @return CommentsInterface
     *
     * @throws \TortillatorAPI\TortillatorBundle\Exception\InvalidFormException
     */
    private function processForm(CommentsInterface $comment, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new CommentsType(), $comment, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $comment = $form->getData();
            $this->om->persist($comment);
            $this->om->flush($comment);

            return $comment;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * Edit a Comment, or create if not exist.
     *
     * @param CommentsInterface $comment
     * @param array         $parameters
     *
     * @return CommentsInterface
     */
    public function put(CommentsInterface $comment, array $parameters)
    {
        return $this->processForm($comment, $parameters, 'PUT');
    }

    /**
     * Partially update a Comment.
     *
     * @param CommentInterface $comment
     * @param array         $parameters
     *
     * @return CommentInterface
     */
    public function patch(CommentsInterface $comment, array $parameters)
    {
        return $this->processForm($comment, $parameters, 'PATCH');
    }

    /**
     * Get a list of Comments.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 10, $offset = 0, $orderby = null)
    {
        return $this->repository->findBy(array(), $orderby, $limit, $offset);
    }

}