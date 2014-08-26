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

use TortillatorAPI\TortillatorBundle\Form\CommentsType;
use TortillatorAPI\TortillatorBundle\Model\CommentsInterface;
use TortillatorAPI\TortillatorBundle\Exception\InvalidFormException;

class CommentsController extends FOSRestController
{
/**
     * List all comments.
     *
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing comments.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many comments to return.")
     *
     * @Annotations\View(
     *  templateVar="comments"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getCommentsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('tortillator_api.comments.handler')->all($limit, $offset);
    }


    /**
     * Get single Comment,
     *
     * @Annotations\View(templateVar="comments")
     *
     * @param Request $request the request object
     * @param integer     $id      the comments id
     *
     * @return array
     *
     * @throws NotFoundHttpException when comments not exist
     */
    public function getCommentAction($id)
    {
        $comment = $this->getOr404($id);

        return $comment;
    }

    public function getCommentsTortillaAction($id)
    {
        $comments = $this->container->get('tortillator_api.comments.handler')->getCommentsByTortilla($id);

        return $comments;
    }


    /**
     * Create a Comment from the submitted data.
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Comment:newComment.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @return FormTypeInterface|View
     */
    public function postCommentAction(Request $request)
    {
        try {
            $newComment = $this->container->get('tortillator_api.comments.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newComment->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_comment', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }


   /**
    * Presents the form to use to create a new comment.
    *
    * @Annotations\View(
    * templateVar = "form"
    * )
    *
    * @return FormTypeInterface
    */
    public function newCommentAction()
    {
        return $this->createForm(new CommentType());
    }

    /**
     * Update existing comment from the submitted data or create a new comments at a specific location.
     *
     *
     * @Annotations\View(
     *  template = "TortillatorAPITortillatorBundle:Comments:editComment.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param integer     $id      the comment id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when comment not exist
     */
    public function putCommentAction(Request $request, $id)
    {
        try {
            if (!($comment = $this->container->get('tortillator_api.comments.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $comment = $this->container->get('tortillator_api.comments.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $comment = $this->container->get('tortillator_api.comments.handler')->put(
                    $comment,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $comment->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_comment', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
    * Update existing comment from the submitted data or create a new comment at a specific location.
    *
    * @Annotations\View(
    * template = "TortillatorAPITortillatorBundle:Comments:editComment.html.twig",
    * templateVar = "form"
    * )
    *
    * @param Request $request the request object
    * @param integer $id the comment id
    *
    * @return FormTypeInterface|View
    *
    * @throws NotFoundHttpException when comment not exist
    */
    public function patchCommentAction(Request $request, $id)
    {
        try {
            $comment = $this->container->get('tortillator_api.comments.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $comment->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_comment', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch the Comment or throw a 404 exception.
     *
     * @param mixed $id
     *
     * @return CommentsInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($comment = $this->container->get('tortillator_api.comments.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $comment;
    }

}
