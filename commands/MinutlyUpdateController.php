<?php

namespace app\commands;

use Yii,
    yii\console\Controller,
    yii\helpers\ArrayHelper,
    app\components\TileCombiner,
    app\components\RegionCombiner,
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
        $baronies = Title::find()
                ->where(['level' => Title::LEVEL_BARONY])
                ->orderBy(['captured' => SORT_ASC])
                ->all();
        
        if ($debug) {
            echo "Start calculate territories for ".count($baronies)." baronies...".PHP_EOL;
        }
        
        /* @var $title Title */
        foreach ($baronies as $title) {
            
            $polygon = TileCombiner::combineList($title->getClaimedTerritory());
            $filepath = Yii::$app->basePath.'/data/polygons/'.$title->id.'.json';
            file_put_contents($filepath, json_encode($polygon));
            
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
        
        $titles = Title::find()
                ->where(['>', 'level', Title::LEVEL_BARONY])
                ->orderBy(['level' => SORT_ASC, 'captured' => SORT_ASC])
                ->all();
        
        if ($debug) {
            echo "Start calculate territories for ".count($titles)." titles...".PHP_EOL;
        }
        
        foreach ($titles as $title) {
            $polygon = RegionCombiner::combine($title->getVassals());
            $filepath = Yii::$app->basePath.'/data/polygons/'.$title->id.'.json';
            file_put_contents($filepath, json_encode($polygon));
        }
        
        
        if ($debug) {
            echo "All tiles saved.".PHP_EOL;
        }
    }
}
