<?php

namespace App\Filters;

class ClientesFilter extends Filter {
    protected array $allowedOperatorsFields = [
        'id' => ['eq'],
        'cliente_id' => ['eq'],
        'nome' => ['eq'],
        'numero' => ['eq'],
        'plano' => ['eq'],
        'observacoes' => ['eq']
    ];
}