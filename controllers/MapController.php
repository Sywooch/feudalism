<?php

namespace app\controllers;

use Yii,
    app\controllers\Controller,
    app\models\titles\Title,
    app\models\Tile;

/**
 * Description of MapController
 *
 * @author i.gorohov
 */
class MapController extends Controller {
    
    public function actionIndex()
    {
        $independentTitles = Title::find()
                                ->where(['suzerainId' => null])
                                ->all();
        return $this->render('default', [
            'independentTitles' => $independentTitles,
        ]);
    }
    
    
    public function actionBuildCastle()
    {
        return $this->render('build-castle');
    }
    
    public function actionGetPolygons(float $minLat, float $maxLat, float $minLng, float $maxLng)
    {
        $tiles = Tile::find()
                ->select(['tiles.*', 'holdings.id as holdingId'])
                ->where(['between', 'centerLat', $minLat, $maxLat])
                ->andWhere(['between', 'centerLng', $minLng, $maxLng])
                ->leftJoin('holdings', 'holdings.tileId = tiles.id')
                ->all();
        $result = [];
        foreach ($tiles as $tile) {
            $result[] = array_merge($tile->displayedAttributes, ['occupied' => $tile->titleId || $tile->holdingId]);
        }
        return $this->renderJson($result);
    }
    
    public function actionGetHoldings(float $minLat, float $maxLat, float $minLng, float $maxLng)
    {
        $tiles = Tile::find()
                ->where(['between', 'centerLat', $minLat, $maxLat])
                ->andWhere(['between', 'centerLng', $minLng, $maxLng])
                ->joinWith('holding')
                ->joinWith('unitGroups')
                ->andWhere('holdings.id IS NOT NULL OR unitsGroups.tileId IS NOT NULL')
                ->all();
        $result = ['holdings' => [], 'armies' => []];
        foreach ($tiles as $tile) {
            /* @var $tile Tile */
            $result['holdings'][] = $tile->holding->displayedAttributes;
            foreach ($tile->unitGroups as $group) {
                $result['armies'][] = $group->displayedAttributes;
            }
        }
        return $this->renderJson($result);
    }
    
}
