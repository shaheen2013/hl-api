<?php

namespace App\Repositories\Satisfactions;

use App\Satisfaction;
use Carbon\Carbon;

class SatisfactionRepository implements SatisfactionRepositoryInterface
{
    protected $satisfaction;

    public function __construct(Satisfaction $satisfaction)
    {
        $this->satisfaction = $satisfaction;
    }

    public function getFromRangeId($fromID, $toID = null)
    {
        $satisfactions = $this->satisfaction->where('done', 1)->where('id', '>=', $fromID);
        if ($toID) {
            $satisfactions = $satisfactions->where('id', '<=', $toID);
        }

        return $satisfactions;
    }

    public function save($satisfactionID, $score, $comment)
    {
        $this->satisfaction->where('id', $satisfactionID)
            ->update(['puntuacion' => $score, 'comentario' => $comment, 'done' => 1, 'fecha_update' => Carbon::now()]);
    }

    public function setReviewSent($satisfactionID)
    {
        $this->satisfaction->where('id', $satisfactionID)->update(['review_send' => 1]);
    }

    public function getSatisfactionByLang($lang, $brand_id, $days, $exclusiveFavorite, $brand_type, $score, $limit)
    {
        $favoriteSelector = '>=';
        if ($exclusiveFavorite) {
            $favoriteSelector = '>';
        }

        $query = Satisfaction::with('user')
            ->whereHas("user", function ($user) use ($lang) {
                $user->where("lang", $lang);
            })
            ->where([
                ["$brand_type", '=', $brand_id],
                ['puntuacion', '>=', $score],
                ['done', '=', 1],
                ['favorite', "$favoriteSelector", 0],
            ])
            ->whereDate('fecha_creado', '>=', date('Y-m-d H:i:s', strtotime('+' . $days . ' days')))
            ->orderBy('user_satisfaction.favorite', 'desc')
            ->orderBy('user_satisfaction.id', 'desc')
            ->take($limit)
            ->get()
            ->load('user.userFacebook')
            ->load('hotel');

        return $query;
    }
}
