<?php

namespace FootballApi\UnitTest\Application\Symfony\Request;

use FootballApi\Application\Symfony\Request\CreateTeamRequestValidator;
use FootballApi\Domain\League\League;
use FootballApi\Domain\League\LeagueRepositoryInterface;
use FootballApi\Domain\Team\Team;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateTeamRequestValidatorTest extends TestCase
{

    public function setUp()
    {
        $this->leagueRepo = $this->getMockBuilder(LeagueRepositoryInterface::class)->getMock();
        $this->teamRepo = $this->getMockBuilder(TeamRepositoryInterface::class)->getMock();
        $this->request = $this->getMockBuilder(Request::class)->getMock();

        $this->validator = new CreateTeamRequestValidator($this->leagueRepo, $this->teamRepo);
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

    public function testItWillThrowExceptionIfCannotDecodePayload()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );
        $this->request->expects($this->once())->method('getContent')->willReturn('{invalid json');
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Invalid payload');
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfPayloadIsNotTheOneExpected()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );
        $this->request->expects($this->once())->method('getContent')->willReturn(json_encode(['name' => 'name']));
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('strip is empty');
        $this->validator->getValidRequestParameters($this->request);

        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );
        $this->request->expects($this->once())->method('getContent')->willReturn(json_encode(['strip' => 'strip']));
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('name is empty');
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfLeagueNotFound()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );
        $this->request->expects($this->once())->method('getContent')->willReturn(
            json_encode(['name' => 'name', 'strip' => 'strip'])
        );

        $this->leagueRepo->expects($this->once())->method('findLeagueById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn(null);
        $this->expectExceptionMessage('League with id 1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3 does not exist');
        $this->expectException(BadRequestHttpException::class);
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfTeamAlreadyExists()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );
        $this->request->expects($this->once())->method('getContent')->willReturn(
            json_encode(['name' => 'name', 'strip' => 'strip'])
        );

        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $this->leagueRepo->expects($this->once())->method('findLeagueById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn($league);

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $this->teamRepo->expects($this->once())->method('findTeamByName')->with('name')->willReturn($team);

        $this->expectExceptionMessage('Team with name name already exists');
        $this->expectException(BadRequestHttpException::class);
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillReturnValidParameters()
    {
        $this->request->expects($this->once())->method('get')->with('leagueId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );
        $this->request->expects($this->once())->method('getContent')->willReturn(
            json_encode(['name' => 'name', 'strip' => 'strip'])
        );

        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $this->leagueRepo->expects($this->once())->method('findLeagueById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn($league);

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $this->teamRepo->expects($this->once())->method('findTeamByName')->with('name')->willReturn(null);

        $params = $this->validator->getValidRequestParameters($this->request);
        $this->assertEquals(
            $params,
            [
                'league' => $league,
                'teamName' => 'name',
                'teamStrip' => 'strip'
            ]
        );
    }

}