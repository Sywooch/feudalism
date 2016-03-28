<?php

namespace app\controllers;

use app\controllers\Controller,
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
        
    public function actionChunk($x, $y)
    {
        $tiles = Tile::findByChunk($x, $y)
                ->with('holding')
                ->with('holding.title.user')
                ->all();
        
        $result = [];
        /* @var $tile Tile */
        foreach ($tiles as $tile) {
            $tileinfo = $tile->getDisplayedAttributes();
            
            if ($tile->holding) {
                $tileinfo['holding'] = $tile->holding->getDisplayedAttributes(true, [
                    'userName',
                    'userLevel'
                ]);
            }
                        
            $result[] = $tileinfo;
        }
        
        return $this->renderJson($result);
    }
}
