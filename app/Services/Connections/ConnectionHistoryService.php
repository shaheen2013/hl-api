<?php

namespace App\Services\Connections;

use App\Repositories\ConnectionHistoryRepositoryInterface;

class ConnectionHistoryService
{
    protected $connectionHistory;

    public function __construct(ConnectionHistoryRepositoryInterface $connectionHistory)
    {
        $this->connectionHistory = $connectionHistory;
    }

    public function deleteConnectionHistoryByUserId(int $userId)
    {
        return $this->connectionHistory->deleteByUserId($userId);
    }

    public function getLastConnection(int $hotelId, int $userId)
    {
        return $this->connectionHistory->getLastConnection($hotelId, $userId);
    }
}
