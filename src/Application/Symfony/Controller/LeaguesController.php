<?php

namespace FootballApi\Application\Symfony\Controller;

use FootballApi\Application\Symfony\Validator\GetTeamsInLeagueRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LeaguesController extends Controller
{
    private $getTeamsInLeagueRequestValidator;

    public function __construct(GetTeamsInLeagueRequestValidator $getTeamsInLeagueRequestValidator)
    {
        $this->getTeamsInLeagueRequestValidator = $getTeamsInLeagueRequestValidator;
    }

    public function getTeamsInLeague(Request $request)
    {
        $leagueId = $request->get('leagueId');

        $this->getTeamsInLeagueRequestValidator->getValidRequestParameters($request);
        die('after');
    }
}