<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Controller;

use FootballApi\Application\Symfony\Validator\CreateTeamRequestValidator;
use FootballApi\Application\Symfony\Validator\GetTeamsInLeagueRequestValidator;
use FootballApi\Domain\Query\QueryBusInterface;
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

    /**
     * TeamsController constructor.
     *
     * @param GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator
     * @param CreateTeamRequestValidator $createTeamRequestValidator
     * @param QueryBusInterface $queryBus
     */
    public function __construct(
        GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator,
        CreateTeamRequestValidator $createTeamRequestValidator,
        QueryBusInterface $queryBus
    ) {
        $this->getTeamsInLeagueRequestValidator = $getTeamsInLeagueRequestValidator;
        $this->createTeamRequestValidator = $createTeamRequestValidator;
        $this->queryBus = $queryBus;
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

    public function createTeam(Request $request)
    {
        $requestParameters = $this->createTeamRequestValidator->getValidRequestParameters($request);

        
    }
}