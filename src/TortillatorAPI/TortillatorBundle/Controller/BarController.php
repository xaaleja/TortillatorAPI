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

use TortillatorAPI\TortillatorBundle\Form\BarType;
use TortillatorAPI\TortillatorBundle\Model\BarInterface;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class BarController  extends FOSRestController
{
     /**
     * List all bars.
     *
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing bars.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many bars to return.")
     *
     * @Annotations\View(
     *  templateVar="bars"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getBarsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('tortillator_api.bar.handler')->all($limit, $offset);
    }


    /**
     * Get single Bar,
     *
     * @Annotations\View(templateVar="bar")
     *
     * @param Request $request the request object
     * @param integer     $integer      the bar id
     *
     * @return array
     *
     * @throws NotFoundHttpException when bar not exist
     */
    public function getBarAction($id)
    {
        $bar = $this->getOr404($id);

        return $bar;
    }

    public function getBarNameAction($id)
    {
        $bar = $this->getBarAction($id);
        return $bar->getName();
    }

    public function getBarLatitudeLongitudeAction($lat, $long)
    {
        if (!($bars = $this->container->get('tortillator_api.bar.handler')->getByLatLong($lat, $long))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$lat));
        }
        //$bars = $this->container->get('tortillator_api.bar.handler')->getByLatLong($lat, $long);

        return $bars;
    }

    public function getBarRecommendationsAction($username, $lat, $long)
    {
        if (!($bars = $this->container->get('tortillator_api.bar.handler')->getRecommendationsByLatLong($username, $lat, $long))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$lat));
        }

        return $bars;
    }

    public function getBarVotedAction($username, $lat, $long)
    {
        if (!($bars = $this->container->get('tortillator_api.bar.handler')->getVotesByLatLong($username, $lat, $long))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$lat));
        }

        return $bars;
    }

    public function getBarBySlugAction($slug)
    {
        $bar = $this->container->get('tortillator_api.bar.handler')->getBarBySlug($slug);
        return $bar;
    }

    /**
     * Create a Bar from the submitted data.
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Bar:newBar.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @return FormTypeInterface|View
     */
    public function postBarAction(Request $request)
    {
        try {
            $newBar = $this->container->get('tortillator_api.bar.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newBar->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_bar', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }


   /**
    * Presents the form to use to create a new bar.
    *
    * @Annotations\View(
    * templateVar = "form"
    * )
    *
    * @return FormTypeInterface
    */
    public function newBarAction()
    {
        return $this->createForm(new BarType());
    }

    /**
     * Update existing bar from the submitted data or create a new bar at a specific location.
     *
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Bar:editBar.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param integer     $id      the bar integer
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when bar not exist
     */
    public function putBarAction(Request $request, $id)
    {
        try {
            if (!($bar = $this->container->get('tortillator_api.bar.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $bar = $this->container->get('tortillator_api.bar.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $bar = $this->container->get('tortillator_api.bar.handler')->put(
                    $bar,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $bar->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_bar', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
    * Update existing bar from the submitted data or create a new bar at a specific location.
    *
    * @Annotations\View(
    * template = "TortillatorAPITortillatorBundle:Bar:editBar.html.twig",
    * templateVar = "form"
    * )
    *
    * @param Request $request the request object
    * @param integer $id the bar id
    *
    * @return FormTypeInterface|View
    *
    * @throws NotFoundHttpException when bar not exist
    */
    public function patchBarAction(Request $request, $id)
    {
        try {
            $bar = $this->container->get('tortillator_api.bar.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'bar' => $bar->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_bar', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch the Bar or throw a 404 exception.
     *
     * @param mixed $id
     *
     * @return BarInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($bar = $this->container->get('tortillator_api.bar.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $bar;
    }

}
