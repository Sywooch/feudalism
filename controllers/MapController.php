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
            $tileinfo = [
                'id' => $tile->id,
                'x' => $tile->x,
                'y' => $tile->y,
                'biome' => $tile->biome,
                'biomeLabel' => $tile->biomeLabel,
                'biomeCharacter' => $tile->biomeCharacter,
                'biomeColor' => $tile->biomeColor,
            ];
            
            if (isset($tile->castles[0])) {
                $castle = $tile->castles[0];
                $tileinfo['castle'] = [
                    'id' => $castle->id,
                    'name' => $castle->name,
                    'userId' => $castle->userId,
                    'userName' => $castle->user->name,
                    'userLevel' => $castle->user->level
                ];
            }
                        
            $result[] = $tileinfo;
        }
        
        return $this->renderJson($result);
    }
}
