<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Manager;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TodoManager.
 */
class TodoManager
{
    private $repository;

    /**
     * TodoManager constructor.
     *
     * @param TodoRepository $repository
     */
    public function __construct(TodoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array mixed
     */
    public function findAll()
    {
        $todos = $this->repository->findBy([], ['id' => 'desc']);

        $list = [];
        /**
         * @var  int $key
         * @var Todo $todo
         */
        foreach ($todos as $key => $todo) {
            $list[$key]['title'] = $todo->getTitle();
            $list[$key]['description'] = $todo->getDescription();
            $list[$key]['isDone'] = $todo->getIsDone();
            $list[$key]['id'] = $todo->getId();
        }

        return $list;
    }

    /**
     * @param Request $request
     *
     * @return Todo
     */
    public function manageTodo(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $todo = $this->repository->find($data['id']);
        $todo = $todo ?? new Todo();
        $todo->setTitle($data['title'])->setDescription($data['description']);

        return $todo;
    }
}