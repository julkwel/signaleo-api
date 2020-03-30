<?php

namespace App\Controller\Security;

use App\Controller\AbstractBaseController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractBaseController
{
    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager, SerializerUtils $serializerUtils, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($entityManager, $serializerUtils);
        $this->csrfTokenManager = $csrfTokenManager;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/token/generate", name="app_get_token")
     *
     * @return Response
     */
    public function getToken()
    {
        return new JsonResponse(['token' => $this->csrfTokenManager->getToken('authenticate')->getValue()]);
    }

    /**
     * @Route("/login/api", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            return new JsonResponse(['status' => 'error', 'error' => $error->getMessage()], Response::HTTP_UNAUTHORIZED);
        } elseif (!$this->getUser()) {
            return new JsonResponse(['status' => 'notfound'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(['status' => 'success', 'user' => ['id' => $this->getUser()->getId()]], Response::HTTP_OK);
    }

    /**
     * @Route("/verif/user",name="verif_user")
     *
     * @param UserRepository $repository
     * @param Request        $request
     *
     * @return JsonResponse
     */
    public function verifUser(UserRepository $repository, Request $request)
    {
        $user = json_decode($request->getContent(), true);
        $user = $repository->findOneBy(['email' => $user['email']]);

        if ($user instanceof User && $user === $this->getUser()) {
            return new JsonResponse(['trust' => 'yes']);
        }

        return new JsonResponse(['trust' => 'no']);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
