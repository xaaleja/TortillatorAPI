<?php

namespace TortillatorAPI\TortillatorBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use TortillatorAPI\TortillatorBundle\Model\UserInterface;
use TortillatorAPI\TortillatorBundle\Form\UserType;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class UserHandler implements UserHandlerInterface
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
     * Get a User.
     *
     * @param mixed $username
     *
     * @return UserInterface
     */
    public function get($username)
    {
        return $this->repository->find($username);
    }

    public function loginUser($username, $password)
    {
        return $this->repository->findOneBy(array('username' => $username, 'password' => $password));
    }

    public function getUserByEmail($email)
    {
        return $this->repository->findOneByEmail($email);
    }

    /**
     * Create a new User.
     *
     * @param array $parameters
     *
     * @return UserInterface
     */
    public function post(array $parameters)
    {
        $user = $this->createUser();

        return $this->processForm($user, $parameters, 'POST');
    }

    private function createUser()
    {
         return new $this->entityClass();
    }

    /**
     * Processes the form.
     *
     * @param UserInterface $user
     * @param array         $parameters
     * @param String        $method
     *
     * @return UserInterface
     *
     * @throws \TortillatorAPI\TortillatorBundle\Exception\InvalidFormException
     */
    private function processForm(UserInterface $user, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new UserType(), $user, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $user = $form->getData();
            $this->om->persist($user);
            $this->om->flush($user);

            return $user;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * Edit a User, or create if not exist.
     *
     * @param UserInterface $user
     * @param array         $parameters
     *
     * @return UserInterface
     */
    public function put(UserInterface $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PUT');
    }

    /**
     * Partially update a User.
     *
     * @param UserInterface $user
     * @param array         $parameters
     *
     * @return UserInterface
     */
    public function patch(UserInterface $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PATCH');
    }

    /**
     * Get a list of Users.
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