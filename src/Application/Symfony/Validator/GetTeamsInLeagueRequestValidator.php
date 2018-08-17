<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Validator;

use FootballApi\Domain\League\LeagueRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Zend\Validator\Digits;
use InvalidArgumentException;

class GetTeamsInLeagueRequestValidator
{

    /** @var LeagueRepositoryInterface $leagueRepository */
    private $leagueRepository;

    /**
     * GetTeamsInLeagueRequestValidator constructor.
     *
     * @param LeagueRepositoryInterface $leagueRepository
     */
    public function __construct(LeagueRepositoryInterface $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getValidRequestParameters(Request $request): array
    {
        $leagueId = $request->get('leagueId');
        $digitsValidator = new Digits();
        if (empty($leagueId) || !$digitsValidator->isValid($leagueId)) {
            throw new InvalidArgumentException('Provided league id is empty or invalid');
        }
        $league = $this->leagueRepository->findOneById((int)$leagueId);

        return [
            'league' => $league
        ];
    }
}