<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use TortillatorAPI\TortillatorBundle\Model\TortillaInterface;
use TortillatorAPI\TortillatorBundle\Form\TortillaType;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class TortillaHandler implements TortillaHandlerInterface
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
     * Get a Tortilla.
     *
     * @param mixed $id
     *
     * @return TortillaInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    public function getbybarid($idBar)
    {
        return $this->repository->findOneByIdBar($idBar);
    }

    public function getUsersTortillas($username)
    {
        return $this->repository->findUsersTortillas($username);
    }

    public function getRanking()
    {
        return $this->repository->findBy(array(), array('average' => 'DESC'), 5, null);
    }

    public function getRecommendations($username)
    {
        return $this->repository->findRecommendations($username);
    }

    /**
     * Create a new Tortilla.
     *
     * @param array $parameters
     *
     * @return TortillaInterface
     */
    public function post(array $parameters)
    {
        $tortilla = $this->createTortilla();

        return $this->processForm($tortilla, $parameters, 'POST');
    }

    private function createTortilla()
    {
         return new $this->entityClass();
    }

    /**
     * Processes the form.
     *
     * @param TortillaInterface $tortilla
     * @param array         $parameters
     * @param String        $method
     *
     * @return TortillaInterface
     *
     * @throws \TortillatorAPI\TortillatorBundle\Exception\InvalidFormException
     */
    private function processForm(TortillaInterface $tortilla, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new TortillaType(), $tortilla, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $tortilla = $form->getData();
            $this->om->persist($tortilla);
            $this->om->flush($tortilla);

            return $tortilla;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * Edit a Tortilla, or create if not exist.
     *
     * @param TortillaInterface $tortilla
     * @param array         $parameters
     *
     * @return TortillaInterface
     */
    public function put(TortillaInterface $tortilla, array $parameters)
    {
        return $this->processForm($tortilla, $parameters, 'PUT');
    }

    /**
     * Partially update a Tortilla.
     *
     * @param TortillaInterface $tortilla
     * @param array         $parameters
     *
     * @return TortillaInterface
     */
    public function patch(TortillaInterface $tortilla, array $parameters)
    {
        return $this->processForm($tortilla, $parameters, 'PATCH');
    }

    /**
     * Get a list of Tortillas.
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