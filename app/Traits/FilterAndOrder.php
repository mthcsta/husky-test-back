<?php

namespace App\Traits;

trait FilterAndOrder
{

    static function addOrders($array, $orders)
    {
        if ($array->count() == 0) {
            return $array;
        }
        $fields = array_keys($array->first()->toArray());
        $ordersFiltered = array_filter($orders, function ($direction, $order) use ($fields) {
            return in_array(strtolower($direction), ['asc', 'desc']) && in_array($order, $fields);
        }, ARRAY_FILTER_USE_BOTH);
        foreach ($ordersFiltered as $order => $direction) {
            $array->orderBy($order, $direction);
        }
        return $array;
    }

    static function addFilters($array, $filters)
    {
        $fields = (new self)->filters;
        $filtersFiltered = array_filter($filters, function ($value, $filter) use ($fields) {
            return in_array($filter, $fields);
        }, ARRAY_FILTER_USE_BOTH);
        foreach ($filtersFiltered as $filter => $value) {
            parent::filter($array, $filter, $value);
        }
        return $array;
    }

}
