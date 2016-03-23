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
                ->with('castles')
                ->with('castles.user')
                ->all();
        
        $result = [];
        /* @var $tile Tile */
        foreach ($tiles as $tile) {
            $tileinfo = $tile->getDisplayedAttributes();
            
            if (isset($tile->castles[0])) {
                $castle = $tile->castles[0];
                $tileinfo['castle'] = $castle->getDisplayedAttributes(true, [
                    'userName',
                    'userLevel'
                ]);
            }
                        
            $result[] = $tileinfo;
        }
        
        return $this->renderJson($result);
    }
}
