<?php

use app\models\titles\Title;

return [
    
    'castle' => [ // Действия с замком
        'build' => 500, // постройка
        'destroy' => 50, // снос
        'fortification-increase' => function(array $params) { // апгрейд фортификаций
            return $params['current']*10;
        },
        'quarters-increase' => function(array $params) { // апгрейд казарм
            return $params['current']*10;
        },
    ],
                
    'unit' => [ // действия с юнитами
        'spawn' => function(array $params) { // создание
            return 100;
        },
    ],     
                
    'title' => [
        'create' => function(array $params) {
            switch ($params['level']) {
                case Title::LEVEL_BARONY:
                    return 300;
            }
        },
    ]
    
];