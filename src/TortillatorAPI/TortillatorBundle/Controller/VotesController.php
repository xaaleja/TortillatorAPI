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

use TortillatorAPI\TortillatorBundle\Form\VotesType;
use TortillatorAPI\TortillatorBundle\Model\VotesInterface;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class VotesController extends FOSRestController
{
/**
     * List all votes.
     *
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing votes.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many votes to return.")
     *
     * @Annotations\View(
     *  templateVar="votes"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getVotesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('tortillator_api.votes.handler')->all($limit, $offset);
    }


    /**
     * Get single Vote,
     *
     * @Annotations\View(templateVar="votes")
     *
     * @param Request $request the request object
     * @param string     $slug      the votes slug
     *
     * @return array
     *
     * @throws NotFoundHttpException when votes not exist
     */
    public function getVoteAction($slug)
    {
        $vote = $this->getOr404($slug);

        return $vote;
    }

    public function getVoteUserRatingAction($slug)
    {
        if (!($vote = $this->container->get('tortillator_api.votes.handler')->get($slug))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$slug));
        }

        return $vote->getRating();
    }

    public function getVotesNumberAction($idTortilla)
    {
        $num = $this->container->get('tortillator_api.votes.handler')->getNumVotes($idTortilla);

        return $num;
    }

    public function getVotesTortillasAction($id)
    {
        $tortillas = $this->container->get('tortillator_api.votes.handler')->getTortillasVotes($id);

        return $tortillas;
    }

    /**
     * Create a Vote from the submitted data.
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Vote:newVote.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @return FormTypeInterface|View
     */
    public function postVoteAction(Request $request)
    {
        try {
            $newVote = $this->container->get('tortillator_api.votes.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'slug' => $newVote->getSlug(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_vote', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }


   /**
    * Presents the form to use to create a new vote.
    *
    * @Annotations\View(
    * templateVar = "form"
    * )
    *
    * @return FormTypeInterface
    */
    public function newVoteAction()
    {
        return $this->createForm(new VoteType());
    }

    /**
     * Update existing vote from the submitted data or create a new votes at a specific location.
     *
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Vote:editVote.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param string     $slug      the vote slug
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when vote not exist
     */
    public function putVoteAction(Request $request, $slug)
    {
        try {
            if (!($vote = $this->container->get('tortillator_api.votes.handler')->get($slug))) {
                $statusCode = Codes::HTTP_CREATED;
                $vote = $this->container->get('tortillator_api.votes.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $vote = $this->container->get('tortillator_api.votes.handler')->put(
                    $vote,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'slug' => $vote->getSlug(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_vote', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
    * Update existing vote from the submitted data or create a new vote at a specific location.
    *
    * @Annotations\View(
    * template = "TortillatorAPITortillatorBundle:Votes:editVote.html.twig",
    * templateVar = "form"
    * )
    *
    * @param Request $request the request object
    * @param string $slug the vote slug
    *
    * @return FormTypeInterface|View
    *
    * @throws NotFoundHttpException when vote not exist
    */
    public function patchVoteAction(Request $request, $slug)
    {
        try {
            $vote = $this->container->get('tortillator_api.votes.handler')->patch(
                $this->getOr404($slug),
                $request->request->all()
            );

            $routeOptions = array(
                'slug' => $vote->getSlug(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_vote', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch the Vote or throw a 404 exception.
     *
     * @param mixed $slug
     *
     * @return VoteInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($slug)
    {
        if (!($vote = $this->container->get('tortillator_api.votes.handler')->get($slug))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$slug));
        }

        return $vote;
    }

}
