<?php
declare(strict_types=1);

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

    public function getValidRequestParameters(Request $request): array
    {
        $leagueId = $request->get('leagueId');

        $league = $this->leagueRepository->findOneById((int)$leagueId);

        return [
            'league' => $league
        ];
    }
}