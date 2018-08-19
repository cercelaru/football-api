<?php

namespace FootballApi\UnitTest\Application\Symfony\Controller\Auth;

use FootballApi\Application\Symfony\Controller\TeamsController;
use FootballApi\Application\Symfony\Request\CreateTeamRequestValidator;
use FootballApi\Application\Symfony\Request\GetTeamsInLeagueRequestValidator;
use FootballApi\Application\Symfony\Request\UpdateTeamRequestValidator;
use FootballApi\Domain\Command\CommandBusInterface;
use FootballApi\Domain\League\League;
use FootballApi\Domain\Query\QueryBusInterface;
use FootballApi\Domain\Team\Command\CreateTeamCommand;
use FootballApi\Domain\Team\Command\UpdateTeamCommand;
use FootballApi\Domain\Team\Query\GetTeamByIdQuery;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQuery;
use FootballApi\Domain\Team\Team;
use FootballApi\Domain\UuidInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamsControllerTest extends TestCase
{

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->getTeamsInLeagueRequestValidator = $this->getMockBuilder(GetTeamsInLeagueRequestValidator::class)
                                                       ->disableOriginalConstructor()
                                                       ->getMock();
        $this->createTeamRequestValidator = $this->getMockBuilder(CreateTeamRequestValidator::class)
                                                 ->disableOriginalConstructor()
                                                 ->getMock();
        $this->updateTeamRequestValidator = $this->getMockBuilder(UpdateTeamRequestValidator::class)
                                                 ->disableOriginalConstructor()
                                                 ->getMock();
        $this->queryBus = $this->getMockBuilder(QueryBusInterface::class)
                               ->disableOriginalConstructor()
                               ->getMock();
        $this->commandBus = $this->getMockBuilder(CommandBusInterface::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->controller = new TeamsController(
            $this->getTeamsInLeagueRequestValidator,
            $this->createTeamRequestValidator,
            $this->updateTeamRequestValidator,
            $this->queryBus,
            $this->commandBus
        );

        parent::setUp();
    }

    public function testItCanFetchTeamsInLeague()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();
        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();
        $uuid = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuid->method('__toString')->willReturn(1234);
        $league->method('getId')->willReturn($uuid);

        $this->getTeamsInLeagueRequestValidator->expects($this->once())->method('getValidRequestParameters')->with(
            $request
        )->willReturn(
            [
                'league' => $league
            ]
        );

        $this->queryBus->expects($this->once())->method('execute')->with(
            $this->callback(
                function (GetTeamsInLeagueQuery $command) {
                    return (string)$command->getLeague()->getId() == 1234;
                }
            )
        )->willReturn(['a' => 'b']);

        $request = $this->getMockBuilder(Request::class)->getMock();
        $response = $this->controller->getTeamsInLeague($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(
            $response->getContent(),
            json_encode(['teams' => ['a' => 'b']])
        );
    }

    public function testItCanCreateTeam()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();
        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();

        $uuid = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuid->method('__toString')->willReturn(1234);
        $league->method('getId')->willReturn($uuid);

        $this->createTeamRequestValidator->expects($this->once())->method('getValidRequestParameters')->with(
            $request
        )->willReturn(
            [
                'league' => $league,
                'teamName' => 'name',
                'teamStrip' => 'strip'
            ]
        );

        $stringUuid = '253e0f90-8842-4731-91dd-0191816e6a28';
        $uuid = Uuid::fromString($stringUuid);
        $uuidFactory = $this->getMockBuilder(UuidFactory::class)->setMethods(['uuid4'])->getMock();
        $uuidFactory->method('uuid4')->willReturn($uuid);
        Uuid::setFactory($uuidFactory);

        $this->commandBus->expects($this->once())->method('handle')->with(
            $this->callback(
                function (CreateTeamCommand $command) {
                    return (string)$command->getLeague()->getId() == 1234
                        && $command->getTeamName() == 'name'
                        && $command->getTeamStrip() == 'strip'
                        && (string)$command->getTeamId() == '253e0f90-8842-4731-91dd-0191816e6a28';
                }
            )
        )->willReturn(null);

        $request = $this->getMockBuilder(Request::class)->getMock();
        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $team->expects($this->once())->method('jsonSerialize')->willReturn(['a' => 'b']);
        $this->queryBus->expects($this->once())->method('execute')->with(
            $this->callback(
                function (GetTeamByIdQuery $query) {
                    return (string)$query->getTeamId() == '253e0f90-8842-4731-91dd-0191816e6a28';
                }
            )
        )->willReturn($team);

        $response = $this->controller->createTeam($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(
            $response->getContent(),
            json_encode(['team' => ['a' => 'b']])
        );
    }

    public function testItCanUpdateTeam()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();

        $uuid = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuid->method('__toString')->willReturn(1234);

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $uuid = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuid->method('__toString')->willReturn(12345);
        $team->method('getId')->willReturn($uuid);

        $this->updateTeamRequestValidator->expects($this->once())->method('getValidRequestParameters')->with(
            $request
        )->willReturn(
            [
                'team' => $team,
                'name' => 'name',
                'strip' => 'strip'
            ]
        );

        $this->commandBus->expects($this->once())->method('handle')->with(
            $this->callback(
                function (UpdateTeamCommand $command) {
                    return (string)$command->getTeam()->getId() == 12345
                        && $command->getNewTeamName() == 'name'
                        && $command->getNewTeamStrip() == 'strip';
                }
            )
        )->willReturn(null);

        $team = $this->getMockBuilder(Team::class)->disableOriginalConstructor()->getMock();
        $team->expects($this->once())->method('jsonSerialize')->willReturn(['a' => 'b']);
        $this->queryBus->expects($this->once())->method('execute')->with(
            $this->callback(
                function (GetTeamByIdQuery $query) {
                    return (string)$query->getTeamId() == 12345;
                }
            )
        )->willReturn($team);

        $response = $this->controller->updateTeam($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(
            $response->getContent(),
            json_encode(['team' => ['a' => 'b']])
        );
    }
}
