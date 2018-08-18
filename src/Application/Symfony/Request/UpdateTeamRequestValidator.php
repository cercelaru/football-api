<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Request;

use FootballApi\Domain\Team\TeamRepositoryInterface;
use FootballApi\Infrastructure\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Zend\Validator\Uuid as UuidValidator;

class UpdateTeamRequestValidator
{

    /** @var TeamRepositoryInterface $teamRepository */
    private $teamRepository;

    /**
     * GetTeamsInLeagueRequestValidator constructor.
     *
     * @param TeamRepositoryInterface $teamRepository
     */
    public function __construct(TeamRepositoryInterface $teamRepository)
    {
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
        $teamId = $request->get('teamId');
        $uuidValidator = new UuidValidator();
        if (empty($teamId) || !$uuidValidator->isValid($teamId)) {
            throw new BadRequestHttpException('Provided team id is empty or invalid');
        }

        $team = $this->teamRepository->findTeamById(new Uuid($teamId));
        if (!$team) {
            throw new BadRequestHttpException(sprintf('Team with id %s does not exist', $teamId));
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

        return [
            'team' => $team,
            'name' => $payload['name'],
            'strip' => $payload['strip']
        ];
    }
}