<?php

namespace FootballApi\UnitTest\Application\Auth;

use Firebase\JWT\JWT;
use FootballApi\Application\Auth\JwtAuthenticator;
use FootballApi\Application\Auth\JwtUserProvider;
use FootballApi\Domain\User\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class JwtAuthenticatorTest extends TestCase
{

    private $authenticator;

    public function setUp()
    {
        $jwtUserProvider = $this->getMockBuilder(JwtUserProvider::class)->getMock();
        $this->authenticator = new JwtAuthenticator($jwtUserProvider, 'testkey');

        parent::setUp();
    }

    public function testItDoesNotCredentialsButReturnsTrue()
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $checkResult = $this->authenticator->checkCredentials([], $user);
        $this->assertTrue($checkResult);
    }

    public function testItKnowsWhatKindOfRequestsItSupports()
    {
        $headerBag1 = $this->getMockBuilder(HeaderBag::class)->getMock();
        $request1 = $this->getMockBuilder(Request::class)->getMock();
        $request1->headers = $headerBag1;
        $headerBag1->expects($this->once())->method('has')->with('Authorization')->willReturn(false);
        $supports = $this->authenticator->supports($request1);
        $this->assertFalse($supports);

        $headerBag2 = $this->getMockBuilder(HeaderBag::class)->getMock();
        $request2 = $this->getMockBuilder(Request::class)->getMock();
        $request2->headers = $headerBag2;
        $headerBag2->expects($this->once())->method('has')->with('Authorization')->willReturn(true);
        $headerBag2->expects($this->once())->method('get')->with('Authorization')->willReturn('noBearer');
        $supports = $this->authenticator->supports($request2);
        $this->assertFalse($supports);

        $headerBag3 = $this->getMockBuilder(HeaderBag::class)->getMock();
        $request3 = $this->getMockBuilder(Request::class)->getMock();
        $request3->headers = $headerBag3;
        $headerBag3->expects($this->once())->method('has')->with('Authorization')->willReturn(true);
        $headerBag3->expects($this->once())->method('get')->with('Authorization')->willReturn('Bearer sometoken');
        $supports = $this->authenticator->supports($request3);
        $this->assertTrue($supports);
    }

    public function testCanRetrieveCredentials()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();

        $headerBag = $this->getMockBuilder(HeaderBag::class)->getMock();

        $request->headers = $headerBag;

        $payload = [
            'iss' => 'Football Api',
            "iat" => (new \DateTime())->modify('-2 hours')->getTimestamp(),
            "nbf" => (new \DateTime())->modify('-2 hours')->getTimestamp(),
            "exp" => (new \DateTime())->modify('+1 hour')->getTimestamp(),
            'sub' => 'generic',
            'userId' => 1,
            'userName' => 'testuser'
        ];

        $jwt = JWT::encode($payload, 'testkey', 'HS256');

        $headerBag->expects($this->once())->method('get')->with('Authorization', '', true)
                  ->willReturn(sprintf('Bearer %s', $jwt));

        $credentials = $this->authenticator->getCredentials($request);

        $this->assertEquals($payload, $credentials);
    }

    public function testItInformsTheUserThatAuthenticationIsRequiredIfTheTokenWasNotProvided()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();

        $response = $this->authenticator->start($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(
            $response->getContent(),
            json_encode(
                [
                    'error' => [
                        'code' => Response::HTTP_FORBIDDEN,
                        'message' => 'Authentication Required'
                    ]
                ]
            )
        );

        $this->assertEquals($response->getStatusCode(), Response::HTTP_UNAUTHORIZED);
    }

    public function testItThrowsAnExceptionIfTheJwtCanNotBeDecoded()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();
        $headerBag = $this->getMockBuilder(HeaderBag::class)->getMock();
        $request->headers = $headerBag;

        $headerBag->expects($this->once())->method('get')->with('Authorization', '', true)
                  ->willReturn(sprintf('Bearer invalidtoken'));

        $this->expectException(CustomUserMessageAuthenticationException::class);

        $this->authenticator->getCredentials($request);
    }
}