<?php

return [
    
    'castle' => [ // Действия с замком
        'build' => 5.0, // постройка
        'destroy' => 1.0, // снос
        'fortification-increase' => function(array $params) {
            return $params['current']*1.0;
        },
    ],
    
];