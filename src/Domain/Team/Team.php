<?php
declare(strict_types=1);

namespace FootballApi\Domain\Team;

use FootballApi\Domain\League\League;
use FootballApi\Domain\UuidInterface;
use JsonSerializable;

class Team implements JsonSerializable
{

    /** @var UuidInterface $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var string $strip */
    private $strip;

    /** @var $league League */
    private $league;

    /**
     * Team constructor.
     *
     * @param UuidInterface $id
     * @param string $name
     * @param string $strip
     * @param League $league
     */
    public function __construct(UuidInterface $id, string $name, string $strip, League $league)
    { echo $id.' ';
        $this->id = $id;
        $this->name = $name;
        $this->strip = $strip;
        $this->league = $league;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getStrip(): string
    {
        return $this->strip;
    }

    /**
     * @param string $name
     *
     * @return Team
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $strip
     *
     * @return Team
     */
    public function setStrip(string $strip): self
    {
        $this->strip = $strip;

        return $this;
    }

    /**
     * @return League|null
     */
    public function getLeague(): ?League
    {
        return $this->league;
    }

    /**
     * @param League|null $league
     *
     * @return Team
     */
    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'strip' => $this->strip
        ];
    }
}
