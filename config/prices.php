<?php

return [
    
    'castle' => [ // Действия с замком
        'build' => 5.0, // постройка
        'destroy' => 1.0, // снос
        'fortification-increase' => function(array $params) { // апгрейд фортификаций
            return $params['current']*1.0;
        },
        'quarters-increase' => function(array $params) { // апгрейд казарм
            return $params['current']*2.0;
        },
    ],
                
    'unit' => [
        'spawn' => function(array $params) { // создание
            switch ($params['protoId']) {
                case 1: // мечники
                    return 2.0;
                case 2: // лучники
                    return 1.0;
                case 3: // конница
                    return 3.0;
            }
        },
    ],
    
];