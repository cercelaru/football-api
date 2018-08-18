<?php
declare(strict_types=1);

namespace FootballApi\Domain\League;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FootballApi\Domain\Team\Team;
use FootballApi\Domain\UuidInterface;

class League
{

    /** @var UuidInterface $id */
    private $id;

    /** @var string $name */
    private $name;

    /** @var ArrayCollection $teams */
    private $teams;


    public function __construct(UuidInterface $uuid, string $name)
    {
        $this->id = $uuid;
        $this->name = $name;
        $this->teams = new ArrayCollection();
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
     * @param string $name
     *
     * @return League
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @param Team $team
     *
     * @return League
     */
    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setLeague($this);
        }

        return $this;
    }

    /**
     * @param Team $team
     *
     * @return League
     */
    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getLeague() === $this) {
                $team->setLeague(null);
            }
        }

        return $this;
    }
}
