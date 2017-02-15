<?php

namespace app\controllers;

use Yii,
    app\controllers\Controller,
    app\models\Tile;

/**
 * Description of MapController
 *
 * @author i.gorohov
 */
class MapController extends Controller {
    
    public function actionIndex()
    {
        return $this->render('default');
    }
    
    
    public function actionBuildCastle()
    {
        return $this->render('build-castle');
    }
    
    public function actionGetPolygons(float $minLat, float $maxLat, float $minLng, float $maxLng)
    {
        $tiles = Tile::find()
                ->where(['between', 'centerLat', $minLat, $maxLat])
                ->andWhere(['between', 'centerLng', $minLng, $maxLng])
                ->all();
        $result = [];
        foreach ($tiles as $tile) {
            $result[] = $tile->displayedAttributes;
        }
        return $this->renderJson($result);
    }
    
}
