<?php

namespace App\Services;
use App\Models\Operator;


class OperatorService
{
    public function getApprovedOperators()
    {
        return Operator::approved()->with('user')->get();
    }

    
}