<?php

use app\models\titles\Title;
use app\models\units\Unit;

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
                case Unit::PROTOTYPE_SWORDMANS: // мечники
                    return 2.0;
                case Unit::PROTOTYPE_BOWMANS: // лучники
                    return 1.0;
                case Unit::PROTOTYPE_HORSEMANS: // конница
                    return 3.0;
            }
        },
    ],
                
    'title' => [
        'create' => function(array $params) {
            switch ($params['level']) {
                case Title::LEVEL_BARONY:
                    return 1.0;
                case Title::LEVEL_COUNT:
                    return 2.0;
                case Title::LEVEL_DUKE:
                    return 5.0;
                case Title::LEVEL_KING:
                    return 10.0;
                case Title::LEVEL_EMPEROR:
                    return 30.0;
            }
        },
    ],
    
];