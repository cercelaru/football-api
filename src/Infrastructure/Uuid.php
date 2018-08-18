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

    /**
     * Uuid constructor.
     *
     * @param string|null $string
     *
     * @throws \Exception
     */
    public function __construct(string $string = null)
    {
        if ($string) {
            $uuid = RamseyUuid::fromString($string);
        } else {
            $uuid = RamseyUuid::uuid4();
        }
        $this->ramseyUuid = $uuid;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->ramseyUuid->toString();
    }
}
