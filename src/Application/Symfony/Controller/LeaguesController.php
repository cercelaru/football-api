<?php

namespace FootballApi\Application\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LeaguesController extends Controller
{
    public function getTeamsInLeague(Request $request)
    {
        $leagueId = $request->get('leagueId');

        die($leagueId);
    }
}