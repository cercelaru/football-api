<?php

namespace FootballApi\Application\Symfony\Validator;

use FootballApi\Domain\Team\TeamRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class GetTeamsInLeagueRequestValidator
{
    private $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function getValidRequestParameters(Request $request)
    {
        $this->teamRepository->
    }
}