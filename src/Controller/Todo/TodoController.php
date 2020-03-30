<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Controller\Todo;

use App\Controller\AbstractBaseController;
use App\Entity\Todo;
use App\Manager\TodoManager;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TodoController.
 */
class TodoController extends AbstractBaseController
{
    /** @var TodoManager */
    private $todoManager;

    /**
     * TodoController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     * @param TodoManager            $manager
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, TodoManager $manager)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->todoManager = $manager;
    }

    /**
     * @Route("/list/todo", name="list_todo")
     *
     * @return JsonResponse
     */
    public function getListTodo()
    {
        $lists = $this->todoManager->findAll();

        return new JsonResponse(['todos' => $lists]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/manage/todo", name="create_todo")
     */
    public function manageTodo(Request $request)
    {
        $todo = $this->todoManager->manageTodo($request);
        if ($this->save($todo)) {
            return new JsonResponse(['status' => 'success', 'todo' => $todo->getId()]);
        }

        return new JsonResponse(['message' => 'error']);
    }

    /**
     * @param Todo $todo
     *
     * @Route("/done/todo/{id}", name="done_todo")
     *
     * @return JsonResponse
     */
    public function finishedTodo(Todo $todo)
    {
        $todo->setIsDone(true);
        if ($this->save($todo)) {
            $lists = $this->todoManager->findAll();

            return new JsonResponse(['status' => 'success', 'todos' => $lists]);
        }

        return new JsonResponse(['status' => 'error']);
    }

    /**
     * @param Todo $todo
     *
     * @return JsonResponse
     *
     * @Route("/delete/todo/{id}", name="delete_todo")
     */
    public function deleteTodo(Todo $todo)
    {
        if ($this->delete($todo)) {
            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error']);
    }

}