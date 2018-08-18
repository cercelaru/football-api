<?php
declare(strict_types=1);

namespace FootballApi\Application\Symfony\Controller;

use FootballApi\Application\Symfony\Request\DeleteLeagueRequestValidator;
use FootballApi\Domain\Command\CommandBusInterface;
use FootballApi\Domain\League\Command\DeleteLeagueCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaguesController extends Controller
{

    /** @var CommandBusInterface $queryBus */
    private $commandBus;

    /** @var DeleteLeagueRequestValidator $deleteLeagueRequestValidator */
    private $deleteLeagueRequestValidator;

    /**
     * LeaguesController constructor.
     *
     * @param CommandBusInterface $commandBus
     * @param DeleteLeagueRequestValidator $deleteLeagueRequestValidator
     */
    public function __construct(
        CommandBusInterface $commandBus,
        DeleteLeagueRequestValidator $deleteLeagueRequestValidator
    ) {
        $this->deleteLeagueRequestValidator = $deleteLeagueRequestValidator;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function deleteLeague(Request $request)
    {
        $requestParameters = $this->deleteLeagueRequestValidator->getValidRequestParameters($request);

        $this->commandBus->handle(new DeleteLeagueCommand($requestParameters['league']));

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}