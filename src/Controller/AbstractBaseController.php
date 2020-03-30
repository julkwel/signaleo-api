<?php
/**
 * @author RAJERISON Julien <julienrajerison@gmail.com>.
 */

namespace App\Controller;

use App\CustomInterface\CustomManagerInterface;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AbstractBaseController.
 */
class AbstractBaseController extends AbstractController implements CustomManagerInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var SerializerUtils */
    protected $serializer;

    /**
     * AbstractBaseController constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param SerializerUtils        $serializerUtils
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializerUtils;
    }

    /**
     * @param object $entityObject
     *
     * @return bool|void
     */
    public function save($entityObject)
    {
        try {
            if (!$entityObject->getId()) {
                $this->entityManager->persist($entityObject);
            }
            $this->entityManager->flush();

            return true;
        } catch (Exception $exception) {
            dd($exception);
            return false;
        }
    }

    /**
     * @param object $entityObject
     *
     * @return bool
     */
    public function update($entityObject)
    {
        try {
            $this->entityManager->flush();

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param object $entityObject
     *
     * @return bool
     */
    public function delete($entityObject)
    {
        try {
            $this->entityManager->remove($entityObject);
            $this->entityManager->flush();

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getList($entityObject)
    {
        // TODO: Implement getList() method.
    }
}
