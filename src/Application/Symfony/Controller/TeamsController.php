<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Controller;

use FootballApi\Application\Symfony\Request\CreateTeamRequestValidator;
use FootballApi\Application\Symfony\Request\GetTeamsInLeagueRequestValidator;
use FootballApi\Application\Symfony\Request\UpdateTeamRequestValidator;
use FootballApi\Domain\Command\CommandBusInterface;
use FootballApi\Domain\Query\QueryBusInterface;
use FootballApi\Domain\Team\Command\CreateTeamCommand;
use FootballApi\Domain\Team\Command\UpdateTeamCommand;
use FootballApi\Domain\Team\Query\GetTeamByIdQuery;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQuery;
use FootballApi\Infrastructure\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /** @var UpdateTeamRequestValidator $updateTeamRequestValidator */
    private $updateTeamRequestValidator;

    /**
     * TeamsController constructor.
     *
     * @param GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator
     * @param CreateTeamRequestValidator $createTeamRequestValidator
     * @param UpdateTeamRequestValidator $updateTeamRequestValidator
     * @param QueryBusInterface $queryBus
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator,
        CreateTeamRequestValidator $createTeamRequestValidator,
        UpdateTeamRequestValidator $updateTeamRequestValidator,
        QueryBusInterface $queryBus,
        CommandBusInterface $commandBus
    ) {
        $this->getTeamsInLeagueRequestValidator = $getTeamsInLeagueRequestValidator;
        $this->createTeamRequestValidator = $createTeamRequestValidator;
        $this->updateTeamRequestValidator = $updateTeamRequestValidator;
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function getTeamsInLeague(Request $request)
    {
        $requestParameters = $this->getTeamsInLeagueRequestValidator->getValidRequestParameters($request);
        $teams = $this->queryBus->execute(new GetTeamsInLeagueQuery($requestParameters['league']));

        return new JsonResponse(['teams' => $teams]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function createTeam(Request $request)
    {
        $requestParameters = $this->createTeamRequestValidator->getValidRequestParameters($request);

        $teamId = new Uuid();
        $this->commandBus->handle(
            new CreateTeamCommand(
                $teamId,
                $requestParameters['league'],
                $requestParameters['teamName'],
                $requestParameters['teamStrip']
            )
        );
        $team = $this->queryBus->execute(new GetTeamByIdQuery($teamId));

        return new JsonResponse(['team' => $team], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateTeam(Request $request)
    {
        $requestParameters = $this->updateTeamRequestValidator->getValidRequestParameters($request);

        $team = $requestParameters['team'];
        $this->commandBus->handle(
            new UpdateTeamCommand(
                $team,
                $requestParameters['name'],
                $requestParameters['strip']
            )
        );
        $team = $this->queryBus->execute(new GetTeamByIdQuery($team->getId()));

        return new JsonResponse(['team' => $team]);
    }
}