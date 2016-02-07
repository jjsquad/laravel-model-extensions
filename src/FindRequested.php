<?php

namespace JJSquad\LaravelModelExtensions;

/**
 * Find models with requested arguments
 *
 * Class FindRequested
 * @package JJSquad\LaravelModelExtensions
 */
trait FindRequested
{
    protected function getFindRequested($perPage, $customFindRequest)
    {
        $query = $this->query();

        $requestedQueries = (is_null($customFindRequest)) ? $this->requestedQueries : $customFindRequest;

        foreach($requestedQueries as $request)
        {
            \Request::input($request['column']) and
            (! isset($request['operator'])) ?
                $query->where($request['column'], str_replace('$1', \Request::input($request['column']), $request['value'])) :
                $query->where($request['column'], $request['operator'], str_replace('$1', \Request::input($request['column']), $request['value']));
        }

        return $query->paginate($perPage);
    }

    static function findRequested($perPage = 15, $customFindRequest = null)
    {
        return parent::getFindRequested($perPage, $customFindRequest);
    }

}