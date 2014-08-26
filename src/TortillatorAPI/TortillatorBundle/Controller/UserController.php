<?php

namespace TortillatorAPI\TortillatorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

use Symfony\Component\Form\FormTypeInterface;

use TortillatorAPI\TortillatorBundle\Form\UserType;
use TortillatorAPI\TortillatorBundle\Model\UserInterface;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class UserController extends FOSRestController
{

    /**
     * List all users.
     *
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many users to return.")
     *
     * @Annotations\View(
     *  templateVar="users"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getUsersAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('tortillator_api.user.handler')->all($limit, $offset);
    }


    /**
     * Get single User,
     *
     * @Annotations\View(templateVar="user")
     *
     * @param Request $request the request object
     * @param string     $string      the user username
     *
     * @return array
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function getUserAction($username)
    {
        $user = $this->getOr404($username);

        return $user;
    }

    public function getUserUsernamePasswordAction($username, $password)
    {
        if (!($user = $this->container->get('tortillator_api.user.handler')->loginUser($username, $password))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$username, $password));
        }

        return $user;
    }

    public function getUserExistAction($username)
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $user = $this->container->get('tortillator_api.user.handler')->get($username);

        if($user!=null)
            $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    public function getUserEmailExistAction($email)
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $user = $this->container->get('tortillator_api.user.handler')->getUserByEmail($email);

        if($user!=null)
            $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }


    /**
     * Create a User from the submitted data.
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:User:newUser.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @return FormTypeInterface|View
     */
    public function postUserAction(Request $request)
    {
        try {
            $newUser = $this->container->get('tortillator_api.user.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'username' => $newUser->getUsername(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }


   /**
    * Presents the form to use to create a new user.
    *
    * @Annotations\View(
    * templateVar = "form"
    * )
    *
    * @return FormTypeInterface
    */
    public function newUserAction()
    {
        return $this->createForm(new UserType());
    }

    /**
     * Update existing user from the submitted data or create a new user at a specific location.
     *
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:User:editUser.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param string     $username      the user string
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function putUserAction(Request $request, $username)
    {
        try {
            if (!($user = $this->container->get('tortillator_api.user.handler')->get($username))) {
                $statusCode = Codes::HTTP_CREATED;
                $user = $this->container->get('tortillator_api.user.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $user = $this->container->get('tortillator_api.user.handler')->put(
                    $user,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'username' => $user->getUsername(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
    * Update existing user from the submitted data or create a new user at a specific location.
    *
    * @Annotations\View(
    * template = "TortillatorAPITortillatorBundle:User:editUser.html.twig",
    * templateVar = "form"
    * )
    *
    * @param Request $request the request object
    * @param string $username the user username
    *
    * @return FormTypeInterface|View
    *
    * @throws NotFoundHttpException when user not exist
    */
    public function patchUserAction(Request $request, $username)
    {
        try {
            $user = $this->container->get('tortillator_api.user.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'user' => $user->getUsername(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch the User or throw a 404 exception.
     *
     * @param mixed $username
     *
     * @return UserInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($username)
    {
        if (!($user = $this->container->get('tortillator_api.user.handler')->get($username))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$username));
        }

        return $user;
    }
}