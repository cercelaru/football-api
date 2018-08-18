<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Request;

use FootballApi\Domain\League\LeagueRepositoryInterface;
use FootballApi\Infrastructure\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @throws \Exception
     */
    public function getValidRequestParameters(Request $request): array
    {
        $leagueId = $request->get('leagueId');
        $uuidValidator = new UuidValidator();
        if (empty($leagueId) || !$uuidValidator->isValid($leagueId)) {
            throw new BadRequestHttpException('Provided league id is empty or invalid');
        }

        $league = $this->leagueRepository->findLeagueById(new Uuid($leagueId));
        if (!$league) {
            throw new BadRequestHttpException(sprintf('League with id %s does not exist', $leagueId));
        }

        return [
            'league' => $league
        ];
    }
}