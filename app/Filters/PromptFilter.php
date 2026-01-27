<?php

namespace App\Filters;

class PromptFilter extends Filter {
    protected array $allowedOperatorsFields = [
        'id' => ['eq'],
        'mensagem' => ['eq'],
    ];
}