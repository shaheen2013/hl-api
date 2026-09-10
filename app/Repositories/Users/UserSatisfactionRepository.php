<?php

namespace App\Repositories\Users;

use App\Satisfaction;
use Illuminate\Support\Facades\Log;

class UserSatisfactionRepository implements UserSatisfactionRepositoryInterface
{
    /**
     * @var Satisfaction
     */
    protected $userSatisfaction;

    /**
     * UserSatisfactionRepository constructor.
     * @param Satisfaction $userSatisfaction
     */
    public function __construct(Satisfaction $userSatisfaction)
    {
        $this->userSatisfaction = $userSatisfaction;
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId)
    {
        return $this->userSatisfaction->where('id_usuario', $userId)->delete();
    }

    public function deleteById($userSatisfactionId)
    {
        try {
            $this->userSatisfaction->where('id', $userSatisfactionId)
            ->update([
                'puntuacion' => 0,
                'comentario' => '',
                'done' => 0,
                'review_send' => 0,
                'fecha_update' => '0000-00-00 00:00:00',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user satisfaction: ' . $e->getMessage());
        }
    }
}
