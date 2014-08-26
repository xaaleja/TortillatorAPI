<?php

namespace TortillatorAPI\TortillatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use TortillatorAPI\TortillatorBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FOS\RestBundle\View\View;


class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TortillatorAPITortillatorBundle:Default:index.html.twig', array('name' => $name));
    }
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('TortillatorAPITortillatorBundle:User')->findAll();

        //return  $this->render('TortillatorAPITortillatorBundle:Default:users.html.twig',array('users' => $users));
        $serializer = $this->container->get('jms_serializer');
        $reports = $serializer->serialize($users, 'json');
        return new Response($reports);
    }
    public function getUserAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('TortillatorAPITortillatorBundle:User')->find($username);

        //return  $this->render('TortillatorAPITortillatorBundle:Default:user.html.twig',array('user' => $user));
        $serializer = $this->container->get('jms_serializer');
        $reports = $serializer->serialize($user, 'json');
        return new Response($reports);

    }
    /**
    *@Route("/new_user", name="new_user")
    *@Method("POST")
    *@Template()
    */
    public function newUserAction(/*Request $request*/)
    {
        /*$user = new User();
        $user->setUsername($request->query->get('username'));
        $user->setPassword($request->query->get('password'));
        $user->setEmail($request->query->get('email'));
        $user->setCity($request->query->get('city'));




        //$user = $this->deserialize('TortillatorAPI\TortillatorBundle\Entity\User', $request);


        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

*/
        //$url = $this->generateUrl('user_get', array('username' => $user->getUsername()), true);

        $user = new User();

        $serializer = $this->container->get('jms_serializer');
        //$reports = $serializer->serialize($user, 'json');
        //return new Response($reports);
        return new Response("POST");
        //$response = new Response();
        //$response->setStatusCode(201);
        ///$response->headers->set('Content-Type', 'application/json');
        //$response->headers->set('Location', $url);
        //return $response;

    }
    protected function deserialize($class, Request $request, $format= 'json')
    {
        $serializer = $this->get('serializer');
        $validator = $this->get('validator');

        try
        {
            $entity = $serializer->deserialize($request->getContent(), $class, $format);
        }
        catch(RuntimeException $e)
        {
            throw new HttpException(400, $e->getMessage());
        }
        if (count($errors = $validator->validate($entity)))
        {
            return $errors;
        }
        return $entity;
    }
}
