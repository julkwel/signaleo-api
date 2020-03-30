<?php

namespace App\Security;

use App\Entity\User;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class UserFirewallAuthenticator.
 */
class UserFirewallAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /** @var UserPasswordEncoderInterface */
    private $passEncoder;

    /** @var SerializerUtils */
    private $serializer;

    /**
     * UserFirewallAuthenticator constructor.
     *
     * @param EntityManagerInterface       $entityManager
     * @param UrlGeneratorInterface        $urlGenerator
     * @param CsrfTokenManagerInterface    $csrfTokenManager
     * @param UserPasswordEncoderInterface $encoder
     * @param SerializerUtils              $serializerUtils
     */
    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $encoder, SerializerUtils $serializerUtils)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passEncoder = $encoder;
        $this->serializer = $serializerUtils;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     *
     * @return array|mixed
     */
    public function getCredentials(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
            'csrf_token' => $data['_csrf_token'],
            'mobile' => $data['mobile'] ?? null,
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return object|UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials['mobile']) {
            $token = new CsrfToken('authenticate', $credentials['csrf_token']);
            if (!$this->csrfTokenManager->isTokenValid($token)) {
                throw new InvalidCsrfTokenException();
            }
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        if ($user instanceof User && !$this->checkCredentials($credentials, $user)) {
            throw new CustomUserMessageAuthenticationException('Password invalid.');
        }

        return $user;
    }

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return JsonResponse|Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        /** @var User $user */
        $user = $token->getUser()->getId();

        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new JsonResponse(['status' => 'success', 'user' => ['id' => $user, 'name' => $token->getUser()->getName()]]);
        }

        return new JsonResponse(['status' => 'success', 'user' => ['id' => $user, 'name' => $token->getUser()->getName()]]);
    }

    /**
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
