<?php

namespace App\Filters;

class UsersFilter extends Filter {
    protected array $allowedOperatorsFields = [
        'id' => ['eq'],
        'name' => ['eq'],
        'email' => ['eq']
    ];
}