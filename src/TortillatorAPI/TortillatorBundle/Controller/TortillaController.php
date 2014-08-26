<?php

namespace TortillatorAPI\TortillatorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

use Symfony\Component\Form\FormTypeInterface;

use TortillatorAPI\TortillatorBundle\Form\TortillaType;
use TortillatorAPI\TortillatorBundle\Model\TortillaInterface;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class TortillaController extends FOSRestController
{
/**
     * List all tortillas.
     *
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing tortillas.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many tortillas to return.")
     *
     * @Annotations\View(
     *  templateVar="tortillas"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getTortillasAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('tortillator_api.tortilla.handler')->all($limit, $offset);
    }


    /**
     * Get single Tortilla,
     *
     * @Annotations\View(templateVar="tortilla")
     *
     * @param Request $request the request object
     * @param integer     $integer      the tortilla id
     *
     * @return array
     *
     * @throws NotFoundHttpException when tortilla not exist
     */
    public function getTortillaAction($id)
    {
        $tortilla = $this->getOr404($id);

        return $tortilla;
    }

    public function getTortillaByBarAction($idBar)
    {
        if (!($tortilla = $this->container->get('tortillator_api.tortilla.handler')->getbybarid($idBar))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$idBar));
        }

        return $tortilla;
    }

    public function getTortillaUserAction($username)
    {
        if (!$tortillas = $this->container->get('tortillator_api.tortilla.handler')->getUsersTortillas($username)) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$username));
        }
        return $tortillas;
    }

    public function getTortillaRankingAction()
    {
        $tortillas = $this->container->get('tortillator_api.tortilla.handler')->getRanking();

        return $tortillas;
    }

    public function getTortillaRecommendationsAction($username)
    {
        $tortillas = $this->container->get('tortillator_api.tortilla.handler')->getRecommendations($username);

        return $tortillas;
    }

    /**
     * Create a Tortilla from the submitted data.
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Tortilla:newTortilla.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @return FormTypeInterface|View
     */
    public function postTortillaAction(Request $request)
    {
        try {
            $newTortilla = $this->container->get('tortillator_api.tortilla.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newTortilla->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_tortilla', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }


   /**
    * Presents the form to use to create a new tortilla.
    *
    * @Annotations\View(
    * templateVar = "form"
    * )
    *
    * @return FormTypeInterface
    */
    public function newTortillaAction()
    {
        return $this->createForm(new TortillaType());
    }

    /**
     * Update existing tortilla from the submitted data or create a new tortilla at a specific location.
     *
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Tortilla:editTortilla.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param integer     $id      the tortilla id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when tortilla not exist
     */
    public function putTortillaAction(Request $request, $id)
    {
        try {
            if (!($tortilla = $this->container->get('tortillator_api.tortilla.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $tortilla = $this->container->get('tortillator_api.tortilla.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $tortilla = $this->container->get('tortillator_api.tortilla.handler')->put(
                    $tortilla,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $tortilla->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_tortilla', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
    * Update existing tortilla from the submitted data or create a new tortilla at a specific location.
    *
    * @Annotations\View(
    * template = "TortillatorAPITortillatorBundle:Tortilla:editTortilla.html.twig",
    * templateVar = "form"
    * )
    *
    * @param Request $request the request object
    * @param integer $id the tortilla id
    *
    * @return FormTypeInterface|View
    *
    * @throws NotFoundHttpException when tortilla not exist
    */
    public function patchTortillaAction(Request $request, $id)
    {
        try {
            $tortilla = $this->container->get('tortillator_api.tortilla.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'tortilla' => $tortilla->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_tortilla', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch the Tortilla or throw a 404 exception.
     *
     * @param mixed $id
     *
     * @return TortillaInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($tortilla = $this->container->get('tortillator_api.tortilla.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $tortilla;
    }

}
