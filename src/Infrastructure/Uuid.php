<?php
declare(strict_types=1);

namespace FootballApi\Infrastructure;

use FootballApi\Domain\UuidInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid implements UuidInterface
{

    /**
     * @var RamseyUuid
     */
    private $ramseyUuid;

    public function __construct()
    {
        $this->ramseyUuid = RamseyUuid::uuid4();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->ramseyUuid->toString();
    }
}
