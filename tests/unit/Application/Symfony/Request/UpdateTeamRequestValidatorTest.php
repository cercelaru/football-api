<?php

namespace FootballApi\UnitTest\Application\Symfony\Request;

use FootballApi\Application\Symfony\Request\UpdateTeamRequestValidator;
use FootballApi\Domain\Team\Team;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateTeamRequestValidatorTest extends TestCase
{

    public function setUp()
    {
        $this->teamRepo = $this->getMockBuilder(TeamRepositoryInterface::class)->getMock();
        $this->request = $this->getMockBuilder(Request::class)->getMock();

        $this->validator = new UpdateTeamRequestValidator($this->teamRepo);
        parent::setUp();
    }

    public function testItWillThrowExceptionIfTeamIdIsEmptyOrInvalid()
    {
        $this->request->expects($this->once())->method('get')->with('teamId')->willReturn(null);
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Provided team id is empty or invalid');
        $this->validator->getValidRequestParameters($this->request);

        $this->request->expects($this->once())->method('get')->with('teamId')->willReturn('string');
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Provided team id is empty or invalid');
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfTeamNotFound()
    {
        $this->request->expects($this->once())->method('get')->with('teamId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );

        $this->teamRepo->expects($this->once())->method('findTeamById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn(null);
        $this->expectExceptionMessage('Team with id 1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3 does not exist');
        $this->expectException(BadRequestHttpException::class);
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfCannotDecodePayload()
    {
        $this->request->expects($this->once())->method('get')->with('teamId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();

        $this->teamRepo->expects($this->once())->method('findTeamById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn($team);

        $this->request->expects($this->once())->method('getContent')->willReturn('{invalid json');
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Invalid payload');
        $this->validator->getValidRequestParameters($this->request);
    }

    public function testItWillThrowExceptionIfPayloadIsNotTheOneExpected()
    {
        $this->request->expects($this->once())->method('get')->with('teamId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();

        $this->teamRepo->expects($this->once())->method('findTeamById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn($team);

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

    public function testItWillReturnValidParameters()
    {
        $this->request->expects($this->once())->method('get')->with('teamId')->willReturn(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        );

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();

        $this->teamRepo->expects($this->once())->method('findTeamById')->with(
            '1a8e7667-cffd-4dbf-ac8c-8c66bf905cc3'
        )->willReturn($team);

        $this->request->expects($this->once())->method('getContent')->willReturn(
            json_encode(['name' => 'name', 'strip' => 'strip'])
        );

        $params = $this->validator->getValidRequestParameters($this->request);
        $this->assertEquals(
            $params,
            [
                'team' => $team,
                'name' => 'name',
                'strip' => 'strip'
            ]
        );
    }

}