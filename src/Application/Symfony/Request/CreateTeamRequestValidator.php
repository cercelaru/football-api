<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Request;

use FootballApi\Domain\League\LeagueRepositoryInterface;
use FootballApi\Domain\Team\TeamRepositoryInterface;
use FootballApi\Infrastructure\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Zend\Validator\Uuid as UuidValidator;

class CreateTeamRequestValidator
{

    /** @var LeagueRepositoryInterface $leagueRepository */
    private $leagueRepository;

    /** @var TeamRepositoryInterface $teamRepository */
    private $teamRepository;

    /**
     * CreateTeamRequestValidator constructor.
     *
     * @param LeagueRepositoryInterface $leagueRepository
     * @param TeamRepositoryInterface $teamRepository
     */
    public function __construct(LeagueRepositoryInterface $leagueRepository, TeamRepositoryInterface $teamRepository)
    {
        $this->leagueRepository = $leagueRepository;
        $this->teamRepository = $teamRepository;
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

        $payload = json_decode($request->getContent(), true);
        if (is_null($payload)) {
            throw new BadRequestHttpException('Invalid payload');
        }

        $requiredPayloadParams = [
            'name',
            'strip'
        ];

        foreach ($requiredPayloadParams as $requiredPayloadParam) {
            if (empty($payload[$requiredPayloadParam])) {
                throw new BadRequestHttpException(sprintf('%s is invalid', $requiredPayloadParam));
            }
        }

        $league = $this->leagueRepository->findLeagueById(new Uuid($leagueId));
        if (!$league) {
            throw new BadRequestHttpException(sprintf('League with id %s does not exist', $leagueId));
        }

        $existingTeam = $this->teamRepository->findTeamByName($payload['name']);
        if ($existingTeam) {
            throw new BadRequestHttpException(sprintf('Team with name %s already exists', $payload['name']));
        }

        return [
            'league' => $league,
            'teamName' => $payload['name'],
            'teamStrip' => $payload['strip']
        ];
    }
}