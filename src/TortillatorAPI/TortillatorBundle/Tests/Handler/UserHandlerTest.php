<?php

namespace TortillatorAPI\TortillatorBundle\Tests\Handler;

use TortillatorAPI\TortillatorBundle\Handler\UserHandler;
use TortillatorAPI\TortillatorBundle\Model\UserInterface;
use TortillatorAPI\TortillatorBundle\Entity\User;

class UserHandlerTest extends \PHPUnit_Framework_TestCase
{
    const USER_CLASS = 'TortillatorAPI\TortillatorBundle\Tests\Handler\DummyUser';

    /** @var UserHandler */
    protected $userHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::USER_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::USER_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::USER_CLASS));


    }

    public function testGet()
    {
        $username = "userTest";
        $user = $this->getUser();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($username))
            ->will($this->returnValue($user));

        $this->userHandler = $this->createUserHandler($this->om, static::USER_CLASS,  $this->formFactory);

        $this->userHandler->get($username);
    }

    public function testPost()
    {
        $username = "user1";
        $password = 'pass1';
        $email = 'email1@gmail.com';
        $city = 'City1';

        $parameters = array('username' => $username, 'password' => $password, 'email' => $email, 'city' => $city);

        $user = $this->getUser();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setCity($city);

        $form = $this->getMock('TortillatorAPI\TortillatorBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($user));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->userHandler = $this->createUserHandler($this->om, static::USER_CLASS,  $this->formFactory);
        $userObject = $this->userHandler->post($parameters);

        $this->assertEquals($userObject, $user);
    }

    /**
     * @expectedException TortillatorAPI\TortillatorBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $username = "user1";
        $password = 'pass1';
        $email = 'email1@gmail.com';
        $city = 'City1';


        $parameters = array('username' => $username, 'password' => $password, 'email' => $email, 'city' => $city);

        $user = $this->getUser();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setCity($city);

        $form = $this->getMock('Acme\BlogBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->userHandler = $this->createUserHandler($this->om, static::USER_CLASS,  $this->formFactory);
        $this->userHandler->post($parameters);
    }


    protected function createUserHandler($objectManager, $userClass, $formFactory)
    {
        return new UserHandler($objectManager, $userClass, $formFactory);
    }

    protected function getUser()
    {
        $userClass = static::USER_CLASS;

        return new $userClass();
    }
}

class DummyUser extends User
{
}
