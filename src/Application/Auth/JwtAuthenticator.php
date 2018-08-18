<?php declare(strict_types=1);

namespace FootballApi\Application\Auth;

use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use InvalidArgumentException;
use UnexpectedValueException;


class JwtAuthenticator extends AbstractGuardAuthenticator
{

    /** @var JwtUserProvider */
    private $jwtUserProvider;

    /** @var string $secretKey */
    private $secretKey;

    /**
     * JwtAuthenticator constructor.
     *
     * @param JwtUserProvider $jwtUserProvider
     * @param string $secretKey
     */
    public function __construct(
        JwtUserProvider $jwtUserProvider,
        string $secretKey
    ) {
        $this->jwtUserProvider = $jwtUserProvider;
        $this->secretKey = $secretKey;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        $authHeader = $request->headers->has('Authorization');
        if ($authHeader) {
            return strpos(trim($request->headers->get('Authorization', '', true)), 'Bearer') === 0;
        }

        return false;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function extractAuthHeader(Request $request)
    {
        $authHeader = trim($request->headers->get('Authorization', '', true));
        if (strpos($authHeader, 'Bearer') === 0) {
            return trim(substr($authHeader, 6));
        }

        return '';
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     *
     * @param Request $request
     *
     * @return array
     *
     */
    public function getCredentials(Request $request)
    {
        $error = null;

        $jwt = $this->extractAuthHeader($request);

        try {
            $decoded = JWT::decode($jwt, $this->secretKey, ['HS256']);
            $jwtPayload = (array)$decoded;
        } catch (InvalidArgumentException $e) {
            $error = 'No JWT was provided';
        } catch (SignatureInvalidException $e) {
            $error = 'Provided JWT was invalid because the signature verification failed';
        } catch (ExpiredException $e) {
            $error = "Provided JWT has since expired, as defined by the 'exp' claim";
        } catch (UnexpectedValueException $e) {
            $error = 'Provided JWT was invalid';
        }

        if ($error) {
            throw new CustomUserMessageAuthenticationException($error, [], Response::HTTP_UNAUTHORIZED);
        }

        return $jwtPayload;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            return $this->jwtUserProvider->createFromJwtPayload($credentials);
        } catch (InvalidArgumentException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid jwt payload', [], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(
            [
                'error' => [
                    'code' => Response::HTTP_FORBIDDEN,
                    'message' => $exception->getMessage()
                ]
            ],
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(
            [
                'error' => [
                    'code' => Response::HTTP_FORBIDDEN,
                    'message' => $authException->getMessage()
                ]
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
