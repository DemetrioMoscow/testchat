<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface ChatRepositoryInterface
{
    public function getLastMessages(int $count = 30): Collection;

    public function getUsersOnline(): Collection;

    public function getUsersTimedOut(): Collection|null;
}
