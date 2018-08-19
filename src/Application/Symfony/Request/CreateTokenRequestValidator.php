<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Request;

use FootballApi\Domain\User\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateTokenRequestValidator
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * CreateTokenRequestValidator constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getValidRequestParameters(Request $request): array
    {
        $payload = json_decode($request->getContent(), true);
        if (is_null($payload)) {
            throw new BadRequestHttpException('Invalid payload');
        }

        $requiredPayloadParams = [
            'username',
            'password'
        ];

        foreach ($requiredPayloadParams as $requiredPayloadParam) {
            if (empty($payload[$requiredPayloadParam])) {
                throw new BadRequestHttpException(sprintf('%s is empty', $requiredPayloadParam));
            }
        }

        $user = $this->userRepository->findUserByUsername($payload['username']);
        if (empty($user)) {
            throw new BadRequestHttpException(sprintf('User with username %s does not exist', $payload['username']));
        }

        if (!$user->isPasswordValid($payload['password'])) {
            throw new BadRequestHttpException(sprintf('Invalid password supplied for user %s', $payload['username']));
        }

        return [
            'user' => $user
        ];
    }
}