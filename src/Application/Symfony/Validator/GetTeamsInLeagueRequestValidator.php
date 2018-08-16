<?php

namespace FootballApi\Application\Symfony\Validator;

use FootballApi\Domain\League\LeagueRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class GetTeamsInLeagueRequestValidator
{
    private $leagueRepository;

    public function __construct(LeagueRepositoryInterface $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    public function getValidRequestParameters(Request $request)
    {
        $leagueId = $request->get('leagueId');

        $league = $this->leagueRepository->findOneById($leagueId);

        echo $league->getName();die('aaa');
    }
}