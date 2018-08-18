<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Validator;

use FootballApi\Domain\League\LeagueRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use InvalidArgumentException;
use Zend\Validator\Uuid as UuidValidator;

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
        $uuidValidator = new UuidValidator();
        if (empty($leagueId) || !$uuidValidator->isValid($leagueId)) {
            throw new InvalidArgumentException('Provided league id is empty or invalid');
        }

        $league = $this->leagueRepository->findOneById($leagueId);
        if (!$league) {
            throw new BadRequestHttpException(sprintf('League with id %s does not exist', $leagueId));
        }

        return [
            'league' => $league
        ];
    }
}