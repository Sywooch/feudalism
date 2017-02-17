<?php

use app\models\titles\Title;
use app\models\units\Unit;

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
            switch ($params['protoId']) {
                case Unit::PROTOTYPE_SWORDMANS:
                    return 100;
                case Unit::PROTOTYPE_BOWMANS:
                    return 200;
                case Unit::PROTOTYPE_HORSEMANS:
                    return 300;
            }
            return 100;
        },
    ],     
                
    'title' => [
        'create' => function(array $params) {
            switch ($params['level']) {
                case Title::LEVEL_BARONY:
                    return 300;
                case Title::LEVEL_COUNT:
                    return 800;
                case Title::LEVEL_DUKE:
                    return 1500;
                case Title::LEVEL_KING:
                    return 5000;
                case Title::LEVEL_EMPEROR:
                    return 10000;
            }
            return 100;
        },
    ]
    
];