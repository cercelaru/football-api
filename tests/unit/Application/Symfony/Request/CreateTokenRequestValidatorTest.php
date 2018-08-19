<?php

namespace FootballApi\UnitTest\Application\Symfony\Request;

use FootballApi\Application\Symfony\Request\CreateTokenRequestValidator;
use FootballApi\Domain\User\UserInterface;
use FootballApi\Domain\User\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateTokenRequestValidatorTest extends TestCase
{

    public function setUp()
    {
        $this->userRepo = $this->getMockBuilder(UserRepositoryInterface::class)->getMock();
        $this->request = $this->getMockBuilder(Request::class)->getMock();

        $this->validator = new CreateTokenRequestValidator($this->userRepo);
        parent::setUp();
    }

    public function testItWillThrowExceptionIfCannotDecodePayload()
    {
        $this->request->expects($this->once())->method('getContent')->willReturn('{invalid json');
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Invalid payload');
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfPayloadIsNotTheOneExpected()
    {
        $this->request->expects($this->once())->method('getContent')->willReturn(json_encode(['username' => 'user']));
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('password is empty');
        $this->validator->getValidRequestParameters($this->request);

        $this->request->expects($this->once())->method('getContent')->willReturn(json_encode(['password' => 'pass']));
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('username is empty');
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfUserNotFound()
    {
        $this->request->expects($this->once())->method('getContent')->willReturn(
            json_encode(['username' => 'username', 'password' => 'pass'])
        );

        $this->userRepo->expects($this->once())->method('findUserByUsername')->with(
            'username'
        )->willReturn(null);
        $this->expectExceptionMessage('User with username username does not exist');
        $this->expectException(BadRequestHttpException::class);
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfPasswordIsInvalid()
    {
        $this->request->expects($this->once())->method('getContent')->willReturn(
            json_encode(['username' => 'username', 'password' => 'pass'])
        );

        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $user->expects($this->once())->method('isPasswordValid')->willReturn(false);
        $this->userRepo->expects($this->once())->method('findUserByUsername')->with(
            'username'
        )->willReturn($user);
        $this->expectExceptionMessage('Invalid password supplied for user username');
        $this->expectException(BadRequestHttpException::class);
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillReturnRequestParameters()
    {
        $this->request->expects($this->once())->method('getContent')->willReturn(
            json_encode(['username' => 'username', 'password' => 'pass'])
        );

        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $user->expects($this->once())->method('isPasswordValid')->willReturn(true);
        $this->userRepo->expects($this->once())->method('findUserByUsername')->with(
            'username'
        )->willReturn($user);
        $params = $this->validator->getValidRequestParameters($this->request);
        $this->assertEquals(
            $params,
            [
                'user' => $user
            ]
        );
    }
}