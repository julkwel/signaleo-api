<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Controller\Actualite;

use App\Controller\AbstractBaseController;
use App\Entity\Actualite;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActualiteCommentController.
 *
 * @Route("/api/comment/actu",name="actualite_comment")
 */
class ActualiteCommentController extends AbstractBaseController
{
    /** @var UserRepository */
    private $userRepos;

    /**
     * ActualiteCommentController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param UserRepository         $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, UserRepository $userRepository)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->userRepos = $userRepository;
    }

    /**
     * @Route("/add/{id}",name="add_comment")
     *
     * @param Request   $request
     * @param Actualite $actualite
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function commentPost(Request $request, Actualite $actualite)
    {
        $comment = new Comment();
        $commentMessage = $request->get('comment');
        $user = $request->get('user');

        $comment->setComment($commentMessage);
        $comment->setUser($this->userRepos->find($user));

        $actualite->addComment($comment);

        if ($this->save($actualite)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/remove/{id}",name="remove_comment")
     *
     * @param Request           $request
     * @param CommentRepository $commentRepository
     * @param Actualite         $actualite
     *
     * @return JsonResponse
     */
    public function removeComment(Request $request, CommentRepository $commentRepository, Actualite $actualite)
    {
        $comment = $request->get('commentId');
        $comment = $commentRepository->find($comment);

        $actualite->removeComment($comment);

        if ($this->save($actualite)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @Route("/response/{id}",name="comment_response")
     *
     * @param Request $request
     * @param Comment $comment
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function repondreComment(Request $request, Comment $comment)
    {
        $user = $request->get('user');
        $commentMessage = $request->get('reply');

        $commentRes = new Comment();
        $commentRes->setUser($this->userRepos->find($user));
        $commentRes->setComment($commentMessage);
        $this->entityManager->persist($commentRes);

        $comment->addComment($commentRes);

        if ($this->save($comment)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }
}
