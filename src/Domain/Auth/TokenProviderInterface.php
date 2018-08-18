<?php

namespace FootballApi\Domain\Auth;

use FootballApi\Domain\User\UserInterface;

interface TokenProviderInterface
{
    public function generateToken(UserInterface $user): string;

}