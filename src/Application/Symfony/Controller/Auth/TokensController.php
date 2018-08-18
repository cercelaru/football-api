<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Controller\Auth;

use FootballApi\Application\Symfony\Request\CreateTokenRequestValidator;
use FootballApi\Domain\Auth\TokenProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokensController extends Controller
{

    /**
     * @var CreateTokenRequestValidator
     */
    private $createTokenRequestValidator;

    /**
     * @var
     */
    private $tokenProvider;

    /**
     * TokensController constructor.
     *
     * @param CreateTokenRequestValidator $createTokenRequestValidator
     * @param TokenProviderInterface $tokenProvider
     */
    public function __construct(
        CreateTokenRequestValidator $createTokenRequestValidator,
        TokenProviderInterface $tokenProvider
    ) {
        $this->createTokenRequestValidator = $createTokenRequestValidator;
        $this->tokenProvider = $tokenProvider;
    }

    public function createToken(Request $request)
    {
        $validRequestParameters = $this->createTokenRequestValidator->getValidRequestParameters($request);
        $token = $this->tokenProvider->generateToken($validRequestParameters['user']);

        return new JsonResponse(['token' => $token]);
    }

}