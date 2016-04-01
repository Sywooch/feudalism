<?php

namespace app\commands;

use yii\console\Controller,
    app\models\titles\Title,
    app\models\Tile;

/**
 * Description of MinutlyUpdateController
 *
 * @author i.gorohov
 */
class MinutlyUpdateController extends Controller {
    
    public function actionIndex($debug = false)
    {
        $this->actionCalcClaimedTerritories($debug);
    }
    
    public function actionCalcClaimedTerritories($debug = false)
    {
        $titles = Title::find()
                ->orderBy(['level' => SORT_ASC, 'captured' => SORT_ASC])
                ->all();
        
        Tile::updateAll(['titleId' => null], 'titleId IS NOT NULL');
        
        $tilesToSave = [];
        
        /* @var $title Title*/
        foreach ($titles as $title) {
            $tiles = $title->getClaimedTerritory();
            foreach ($tiles as $tile) {
                if (!isset($tilesToSave[$tile->id])) {
                    $tilesToSave[$tile->id] = $tile;
                    $tilesToSave[$tile->id]->titleId = $title->id;
                }
            }
        }
        
        if ($debug) {
            $count = count($tilesToSave);
            echo "{$count} tiles will be saved:".PHP_EOL;
        }
        
        $i = 0;
        foreach ($tilesToSave as $id => $tile) {
            $tile->save();
            $i++;
            if ($debug && $i%10) {
                echo "Saved {$i}/{$count} tiles".PHP_EOL;
            }
        }
        
        if ($debug) {
            echo "All tiles saved.".PHP_EOL;
        }
    }
}
