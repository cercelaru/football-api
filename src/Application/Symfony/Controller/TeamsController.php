<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Controller;

use FootballApi\Application\Symfony\Validator\CreateTeamRequestValidator;
use FootballApi\Application\Symfony\Validator\GetTeamsInLeagueRequestValidator;
use FootballApi\Domain\Command\CommandBusInterface;
use FootballApi\Domain\Query\QueryBusInterface;
use FootballApi\Domain\Team\Command\CreateTeamCommand;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamsController extends Controller
{

    /** @var GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator */
    private $getTeamsInLeagueRequestValidator;

    /** @var QueryBusInterface $queryBus */
    private $queryBus;

    /** @var CreateTeamRequestValidator $createTeamRequestValidator */
    private $createTeamRequestValidator;

    /** @var CommandBusInterface $commandBus */
    private $commandBus;

    /**
     * TeamsController constructor.
     *
     * @param GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator
     * @param CreateTeamRequestValidator $createTeamRequestValidator
     * @param QueryBusInterface $queryBus
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator,
        CreateTeamRequestValidator $createTeamRequestValidator,
        QueryBusInterface $queryBus,
        CommandBusInterface $commandBus
    ) {
        $this->getTeamsInLeagueRequestValidator = $getTeamsInLeagueRequestValidator;
        $this->createTeamRequestValidator = $createTeamRequestValidator;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getTeamsInLeague(Request $request)
    {
        $requestParameters = $this->getTeamsInLeagueRequestValidator->getValidRequestParameters($request);
        $teams = $this->queryBus->execute(new GetTeamsInLeagueQuery($requestParameters['league']));

        return new JsonResponse(['teams' => $teams]);
    }

    /**
     * @param Request $request
     */
    public function createTeam(Request $request)
    {
        $requestParameters = $this->createTeamRequestValidator->getValidRequestParameters($request);

        $this->commandBus->handle(
            new CreateTeamCommand(
                $requestParameters['league'],
                $requestParameters['teamName'],
                $requestParameters['teamStrip']
            )
        );
        die('after');
    }
}