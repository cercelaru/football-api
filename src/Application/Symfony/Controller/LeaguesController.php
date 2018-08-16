<?php

namespace FootballApi\Application\Symfony\Controller;

use FootballApi\Application\Symfony\Validator\GetTeamsInLeagueRequestValidator;
use FootballApi\Domain\Query\QueryBusInterface;
use FootballApi\Domain\Team\Query\GetTeamsInLeagueQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LeaguesController extends Controller
{

    private $getTeamsInLeagueRequestValidator;

    private $queryBus;

    public function __construct(
        GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator,
        QueryBusInterface $queryBus
    ) {
        $this->getTeamsInLeagueRequestValidator = $getTeamsInLeagueRequestValidator;
        $this->queryBus = $queryBus;
    }

    public function getTeamsInLeague(Request $request)
    {
        $params = $this->getTeamsInLeagueRequestValidator->getValidRequestParameters($request);

        $teams = $this->queryBus->execute(new GetTeamsInLeagueQuery($params['league']));

        return new JsonResponse($teams);
    }
}