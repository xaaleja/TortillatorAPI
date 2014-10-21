<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use TortillatorAPI\TortillatorBundle\Model\BarInterface;
use TortillatorAPI\TortillatorBundle\Form\BarType;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class BarHandler implements BarHandlerInterface
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
     * Get a Bar.
     *
     * @param mixed $id
     *
     * @return BarInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    public function getByLatLong($lat, $long)
    {
        return $this->repository->findBarsNearLocation($lat, $long);
    }

    public function getBarBySlug($slug)
    {
        return $this->repository->findOneBySlug($slug);
    }

    public function getRecommendationsByLatLong($username, $lat, $long)
    {
        return $this->repository->findRecommendedBarsNearLocation($username, $lat, $long);
    }

    public function getVotesByLatLong($username, $lat, $long)
    {
        return $this->repository->findVotedBarsNearLocation($username, $lat, $long);
    }
    /**
     * Create a new Bar.
     *
     * @param array $parameters
     *
     * @return BarInterface
     */
    public function post(array $parameters)
    {
        $bar = $this->createBar();

        return $this->processForm($bar, $parameters, 'POST');
    }

    private function createBar()
    {
         return new $this->entityClass();
    }

    /**
     * Processes the form.
     *
     * @param BarInterface $bar
     * @param array         $parameters
     * @param String        $method
     *
     * @return BarInterface
     *
     * @throws \TortillatorAPI\TortillatorBundle\Exception\InvalidFormException
     */
    private function processForm(BarInterface $bar, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new BarType(), $bar, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $bar = $form->getData();
            $this->om->persist($bar);
            $this->om->flush($bar);

            return $bar;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * Edit a Bar, or create if not exist.
     *
     * @param BarInterface $bar
     * @param array         $parameters
     *
     * @return BarInterface
     */
    public function put(BarInterface $bar, array $parameters)
    {
        return $this->processForm($bar, $parameters, 'PUT');
    }

    /**
     * Partially update a Bar.
     *
     * @param BarInterface $bar
     * @param array         $parameters
     *
     * @return BarInterface
     */
    public function patch(BarInterface $bar, array $parameters)
    {
        return $this->processForm($bar, $parameters, 'PATCH');
    }

    /**
     * Get a list of Bars.
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