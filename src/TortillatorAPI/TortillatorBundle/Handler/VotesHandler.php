<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use TortillatorAPI\TortillatorBundle\Model\VotesInterface;
use TortillatorAPI\TortillatorBundle\Form\VotesType;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class VotesHandler implements VotesHandlerInterface
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
     * Get a Vote.
     *
     * @param mixed $slug
     *
     * @return VotesInterface
     */
    public function get($slug)
    {
        return $this->repository->findOneBy(array('slug' => $slug));
    }

    public function getNumVotes($idTortilla)
    {
        return $this->repository->findNumVotesTortilla($idTortilla);
    }


    /**
     * Create a new Vote.
     *
     * @param array $parameters
     *
     * @return VoteInterface
     */
    public function post(array $parameters)
    {
        $vote = $this->createVote();

        return $this->processForm($vote, $parameters, 'POST');
    }

    private function createVote()
    {
         return new $this->entityClass();
    }

    /**
     * Processes the form.
     *
     * @param VotesInterface $vote
     * @param array         $parameters
     * @param String        $method
     *
     * @return VotesInterface
     *
     * @throws \TortillatorAPI\TortillatorBundle\Exception\InvalidFormException
     */
    private function processForm(VotesInterface $vote, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new VotesType(), $vote, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $vote = $form->getData();
            $this->om->persist($vote);
            $this->om->flush($vote);

            return $vote;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * Edit a Vote, or create if not exist.
     *
     * @param VotesInterface $vote
     * @param array         $parameters
     *
     * @return VotesInterface
     */
    public function put(VotesInterface $vote, array $parameters)
    {
        return $this->processForm($vote, $parameters, 'PUT');
    }

    /**
     * Partially update a Vote.
     *
     * @param VotesInterface $vote
     * @param array         $parameters
     *
     * @return VotesInterface
     */
    public function patch(VotesInterface $vote, array $parameters)
    {
        return $this->processForm($vote, $parameters, 'PATCH');
    }

    /**
     * Get a list of Votes.
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