<?php

namespace App\Filters;

use Exception;
use Illuminate\Http\Request;

abstract class Filter {

    protected array $allowedOperatorsFields;

    protected array $translateOperatorsFields = [
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'eq' => '=',
        'ne' => '!=',
    ];

    public function filter(Request $request) {

        $where = [];
        $whereIn = [];

        foreach ($this->allowedOperatorsFields as $param => $operators) {
            $queryOperator = $request->query($param);
            if ($queryOperator) {
                foreach ($queryOperator as $operator => $value) {
                    if (!in_array($operator, $operators)) {
                        throw new Exception("{$param} não tem um {$operator} operador");
                    }
                    
                    if (str_contains($value, '[')) {
                        $whereIn[] = [
                            $param,
                            explode(',', str_replace(['[', ']'], ['',''], $value)),
                            $value
                        ];
                    }
                    else {
                        $where[] = [
                            $param,
                            $this->translateOperatorsFields[$operator],
                            $value
                        ];
                    }
                }
            }
        }

        if (empty($where) && empty($whereIn)) {
            return [];
        }

        return [
            'where' => $where,
            'whereIn' => $whereIn
        ];
    }
}