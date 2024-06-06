<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use Illuminate\Http\Request;

class CustomersFilter extends ApiFilter
{
    protected array $safeParams = [
        "id" => ["eq"],
        "name" => ["eq"],
        "type" => ["eq"],
        "email" => ["eq"],
        "address" => ["eq"],
        "city" => ["eq"],
        "state" => ["eq"],
        "postalCode" => ["eq", "gt", "lt"],
    ];

    protected array $columnMap = [
        "postalCode" => "postal_code",
    ];

    protected array $operatorMap = [
        "eq" => "=",
        "lt" => "<",
        "lte" => "<=",
        "gt" => ">",
        "gte" => ">=",
        "ne" => "!=",
    ];
}
