<?php

namespace App\Filters;

class EmpresasFilter extends Filter
{
    protected array $allowedOperatorsFields = [
        'id' => ['eq'],
    ];
}
