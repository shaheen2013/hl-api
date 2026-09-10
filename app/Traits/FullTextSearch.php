<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @param bool $booleanMode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullTextSearch($query, $term, $booleanMode = true, $inFieldList = null)
    {
        $columns = implode(',', $this->searchable);
        $mode = $booleanMode ? 'IN BOOLEAN' : 'IN NATURAL LANGUAGE';
        $query->whereRaw(
            "MATCH ({$columns}) AGAINST (? {$mode} MODE)",
            $this->fullTextWildcards($term)
        );

        if ($inFieldList) {
            $query = $query->addSelect(DB::raw("MATCH ({$columns}) AGAINST ('{$this->fullTextWildcards($term)}' {$mode} MODE) AS {$inFieldList}"));
        }

        return $query;
    }
}
