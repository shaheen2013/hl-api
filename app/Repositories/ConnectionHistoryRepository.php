<?php

namespace App\Repositories;

use App\ConnectionHistory;

class ConnectionHistoryRepository implements ConnectionHistoryRepositoryInterface
{
    /**
     * @var ConnectionHistory
     */
    protected $connectionHistory;

    /**
     * ConnectionHistoryRepository constructor.
     * @param ConnectionHistory $connectionHistory
     */
    public function __construct(ConnectionHistory $connectionHistory)
    {
        $this->connectionHistory = $connectionHistory;
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId)
    {
        return $this->connectionHistory->where('id_user', $userId)->delete();
    }

    public function getLastConnection(int $hotelId, int $userId)
    {
        return $this->connectionHistory->where([
            'id_hotel'  => $hotelId,
            'id_user'   => $userId
        ])->orderBy('id', 'desc')->first();
    }
}
