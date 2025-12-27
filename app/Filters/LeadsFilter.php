<?php

namespace App\Filters;

class LeadsFilter extends Filter {
    protected array $allowedOperatorsFields = [
        'id' => ['eq'],
        'cliente_id' => ['eq'],
        'nome' => ['eq'],
        'numero' => ['eq'],
        'status' => ['eq'],
        'observacoes' => ['eq']
    ];
}