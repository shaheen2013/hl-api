<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait Filters
{
    public function filterByRelevance($query, string $keyField, string $search, array $models)
    {
        $subqueries = $this->getSubqueries($search, $models);
        $tempQuery = null;

        foreach ($subqueries as $subquery) {
            $tempQuery = $tempQuery ? $tempQuery->union($subquery) : $subquery;
        }

        $filter = DB::table(DB::raw("({$tempQuery->toSql()}) AS search"))
            ->select('id')
            ->selectRaw('SUM(relevance) AS relevance')
            ->groupBy('id')
            ->orderBy('relevance', 'desc');

        $query = $query->whereIn($keyField, function ($query) use ($filter, $tempQuery) {
            $query->select('id')
                ->from(DB::raw("({$filter->toSql()}) AS filter"))
                ->mergeBindings($tempQuery->getQuery());
        });

        return $query;
    }

    private function getSubqueries(string $search, array $models)
    {
        $queries = [];
        foreach ($models as $key => $options) {
            $modelName = is_array($options) ? $key : $options;
            $key = $options['key'] ?? null;
            $queries[] = $this->getSubquery($search, $modelName, $key);
        }

        return $queries;
    }

    private function getSubquery(string $search, string $modelName, $key = null)
    {
        $model = new $modelName();
        $key = $key ?? $model->getKeyName();
        $model = $model->select("{$key} AS id")->fullTextSearch($search, false, 'relevance');

        return $model;
    }
}

/*
The text string is searched in the different fields of the indicated models,
the model must have those fields in $searchable, a union is created and it is grouped by id.
The result is a list of indexes sorted by relevance that will be used for the filter of the main query.

For example, searching for the KAREN string in the User and Satisfaction models would result in the next UNION:

    SELECT
        id,
        SUM(relevance) AS relevance
    FROM
    (
        SELECT
            id AS id,
            MATCH(users.nombre, users.email) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE) AS relevance
        FROM
            users
        WHERE
            MATCH(users.nombre, users.email) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE)
        UNION
        SELECT
            id_usuario AS id,
            MATCH(user_satisfaction.comentario) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE) AS relevance
        FROM
            user_satisfaction
        WHERE
            MATCH(user_satisfaction.comentario) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE)
    ) AS search
    GROUP BY
        id
    ORDER BY
        relevance
    DESC

The result can be used to filter by index. In the case of SatisfactionSurveyProductService it would look like this:

    SELECT
        users.*,
        hoteles.*,
        user_satisfaction.*
    FROM
        user_satisfaction
    INNER JOIN
        users
    ON
        user_satisfaction.id_usuario = users.id
    INNER JOIN
        hoteles
    ON
        user_satisfaction.id_hotel = hoteles.id
    WHERE
        id_hotel = 252 AND
        user_satisfaction.done = 1 AND
        users.id IN
        (
            SELECT
                id
            FROM
                (
                SELECT
                    id,
                    SUM(relevance) AS relevance
                FROM
                (
                    SELECT
                        id AS id,
                        MATCH(users.nombre, users.email) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE) AS relevance
                    FROM
                        users
                    WHERE
                        MATCH(users.nombre, users.email) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE)
                    UNION
                    SELECT
                        id_usuario AS id,
                        MATCH(user_satisfaction.comentario) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE) AS relevance
                    FROM
                        user_satisfaction
                    WHERE
                        MATCH(user_satisfaction.comentario) AGAINST ('+KAREN*' IN NATURAL LANGUAGE MODE)
                ) AS search
                GROUP BY
                    id
                ORDER BY
                    relevance
                DESC
            ) AS filter
        )
    ORDER BY
        fecha_update DESC

*/
