<?php

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

function sanitize_pagination_params(Request $request): array
{
    static $DEFAULT_PER_PAGE = 5;

    static $MAX_PER_PAGE = 100;

    static $MIN_PER_PAGE = 1;

    static $DEFAULT_PAGE = 1;

    $page = max($DEFAULT_PAGE, (int) $request->input('page', $DEFAULT_PAGE));

    $perPage = $request->input('per_page', $DEFAULT_PER_PAGE);

    $perPage = (int) $perPage;

    if (
        $perPage < $MIN_PER_PAGE ||
        $perPage > $MAX_PER_PAGE
    ) {
        $perPage = $DEFAULT_PER_PAGE;
    }

    return [
        'page' => $page,
        'per_page' => $perPage,
    ];
}


function paginate_query(Builder $query, array $params): LengthAwarePaginator
{
    return $query->paginate(
        $params['per_page'],
        ['*'],
        'page',
        $params['page']
    );
}


function create_paginated_response(Builder $query, Request $request): LengthAwarePaginator
{
    $params = sanitize_pagination_params($request);
    return paginate_query($query, $params);
}

