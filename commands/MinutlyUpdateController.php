<?php

namespace app\commands;

use yii\console\Controller,
    yii\helpers\ArrayHelper,
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
        
        if ($debug) {
            echo "Start calculate territories for ".count($titles)." titles...".PHP_EOL;
        }
        
        /* @var $title Title*/
        foreach ($titles as $title) {
            $claimedTiles = [];
            $unclaimedTiles = [];
            foreach ($title->getClaimedTerritory() as $tile) {
                $tileId = (int)$tile->id;
                $claimedTiles[$tileId] = $tileId;
            }
            foreach ($title->tiles as $tile) {
                $tileId = (int)$tile->id;
                if (isset($claimedTiles[$tileId])) {
                    unset($claimedTiles[$tileId]);
                } else {
                    $unclaimedTiles[$tileId] = $tileId;
                }
            }
            Tile::updateAll(['titleId' => null], ['in', 'id', $unclaimedTiles]);
            Tile::updateAll(['titleId' => $title->id], ['in', 'id', $claimedTiles]);
            
            if ($debug) {
                echo "Title {$title->fullName} updated.".PHP_EOL;
            }
        }
        
        
        if ($debug) {
            echo "All tiles saved.".PHP_EOL;
        }
    }
}
