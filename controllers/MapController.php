<?php

namespace app\controllers;

use Yii,
    yii\web\HttpException,
    yii\web\NotFoundHttpException,
    app\controllers\Controller,
    app\models\titles\Title,
    app\models\Tile,
    app\models\units\UnitGroup;

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
    
    public function actionMoveArmy(int $id)
    {
        $army = UnitGroup::findOne($id);
        if (is_null($army)) {
            throw new NotFoundHttpException(Yii::t('app', 'Army not found'));
        }
        if (!$army->isOwner($this->user)) {
            throw new HttpException(403, Yii::t('app', 'Action not allowed'));
        }
        
        return $this->render('move-army', [
            'army' => $army,
            'user' => $this->user,
        ]);
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
            $result['holdings'][] = $tile->holding->getDisplayedAttributes($tile->holding->isOwner($this->user));
            foreach ($tile->unitGroups as $group) {
                $result['armies'][] = $group->getDisplayedAttributes($group->isOwner($this->user));
            }
        }
        return $this->renderJson($result);
    }
    
}
