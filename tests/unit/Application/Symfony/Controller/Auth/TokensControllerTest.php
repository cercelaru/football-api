<?php

namespace FootballApi\UnitTest\Application\Symfony\Controller\Auth;

use FootballApi\Application\Symfony\Controller\Auth\TokensController;
use FootballApi\Application\Symfony\Request\CreateTokenRequestValidator;
use FootballApi\Domain\Auth\TokenProviderInterface;
use FootballApi\Domain\User\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TokensControllerTest extends TestCase
{

    public function testItCanCreateTokenIfTheSuppliedParametersAreValid()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();
        $validator = $this->getMockBuilder(CreateTokenRequestValidator::class)->disableOriginalConstructor()->getMock();
        $tokenProvider = $this->getMockBuilder(TokenProviderInterface::class)->getMock();

        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $tokenProvider->expects($this->once())->method('generateToken')->with($user)->willReturn('testtoken');

        $validator->expects($this->once())->method('getValidRequestParameters')->with($request)->willReturn(
            [
                'user' => $user
            ]
        );

        $controller = new TokensController($validator, $tokenProvider);

        $request = $this->getMockBuilder(Request::class)->getMock();
        $response = $controller->createToken($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($response->getContent(), json_encode(['token' => 'testtoken']));
    }
}
