<?php

namespace App\Traits;

trait PageNumber
{

    public static function pageNumber($offset, $limit)
    {
        return ceil(($offset + 1) / $limit);
    }

}
