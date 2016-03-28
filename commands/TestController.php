<?php

namespace app\commands;

use yii\console\Controller,
    app\components\ExperienceCalculator;

class TestController extends Controller
{
    public function actionIndex()
    {
        for ($i = 0; $i < 20; $i++) {
            echo $i.' lvl. = '.number_format(ExperienceCalculator::getExperienceByLevel($i),0,' ',' ').PHP_EOL;
        }
        
        echo PHP_EOL;
        
        foreach ([
            0,
            10,
            1234,
            699999,
            700000,
            700001
        ] as $e) {
            echo $e.' = '.ExperienceCalculator::getLevelByExperience($e).' lvl.'.PHP_EOL;
        }
    }
}
