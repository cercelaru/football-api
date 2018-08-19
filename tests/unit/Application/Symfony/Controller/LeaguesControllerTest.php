<?php

namespace FootballApi\UnitTest\Application\Symfony\Controller\Auth;

use FootballApi\Application\Symfony\Controller\LeaguesController;
use FootballApi\Application\Symfony\Request\DeleteLeagueRequestValidator;
use FootballApi\Domain\Command\CommandBusInterface;
use FootballApi\Domain\League\Command\DeleteLeagueCommand;
use FootballApi\Domain\League\League;
use FootballApi\Domain\UuidInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaguesControllerTest extends TestCase
{

    public function testItCanDeleteLeague()
    {
        $request = $this->getMockBuilder(Request::class)->getMock();
        $validator = $this->getMockBuilder(DeleteLeagueRequestValidator::class)->disableOriginalConstructor()->getMock(
        );
        $commandBus = $this->getMockBuilder(CommandBusInterface::class)->getMock();

        $league = $this->getMockBuilder(League::class)->disableOriginalConstructor()->getMock();

        $uuid = $this->getMockBuilder(UuidInterface::class)->getMock();
        $uuid->method('__toString')->willReturn(1234);
        $league->method('getId')->willReturn($uuid);

        $validator->expects($this->once())->method('getValidRequestParameters')->with($request)->willReturn(
            [
                'league' => $league
            ]
        );

        $commandBus->expects($this->once())->method('handle')->with(
            $this->callback(
                function (DeleteLeagueCommand $command) {
                    return (string)$command->getLeague()->getId() == 1234;
                }
            )
        );

        $controller = new LeaguesController($commandBus, $validator);

        $request = $this->getMockBuilder(Request::class)->getMock();
        $response = $controller->deleteLeague($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
