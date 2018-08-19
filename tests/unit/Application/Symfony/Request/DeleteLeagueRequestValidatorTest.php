<?php

namespace FootballApi\UnitTest\Application\Symfony\Request;

use FootballApi\Application\Symfony\Request\DeleteLeagueRequestValidator;
use FootballApi\Domain\League\League;
use FootballApi\Domain\League\LeagueRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeleteLeagueRequestValidatorTest extends TestCase
{

    public function setUp()
    {
        $this->leagueRepo = $this->getMockBuilder(LeagueRepositoryInterface::class)->getMock();
        $this->request = $this->getMockBuilder(Request::class)->getMock();

        $this->validator = new DeleteLeagueRequestValidator($this->leagueRepo);
        parent::setUp();
    }

    public function testItWillThrowExceptionIfLeagueIdIsEmptyOrInvalid()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(null);
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Provided league id is empty or invalid');
        $this->validator->getValidRequestParameters($this->request);

        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn('string');
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Provided league id is empty or invalid');
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfLeagueNotFound()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );

        $this->leagueRepo->expects($this->once())->method('findLeagueById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn(null);
        $this->expectExceptionMessage('League with id 1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3 does not exist');
        $this->expectException(BadRequestHttpException::class);
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillReturnValidParameters()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );
        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $this->leagueRepo->expects($this->once())->method('findLeagueById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn($league);
        $params = $this->validator->getValidRequestParameters($this->request);
        $this->assertEquals(
            $params,
            [
                'league' => $league
            ]
        );
    }

}