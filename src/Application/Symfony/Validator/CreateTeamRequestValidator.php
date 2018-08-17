<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Validator;

use FootballApi\Domain\League\LeagueRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Zend\Validator\Digits;

class CreateTeamRequestValidator
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

        $league = $this->leagueRepository->findOneById((int)$leagueId);
        if (!$league) {
            throw new BadRequestHttpException(sprintf('League with id %d does not exist', $leagueId));
        }

        return [
            'league' => $league,
            'teamName' => $payload['name'],
            'teamStrip' => $payload['strip']
        ];
    }
}