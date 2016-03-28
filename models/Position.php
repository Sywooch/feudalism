<?php

namespace app\models;

use app\models\Tile;

/**
 *
 * @property Tile $tile
 * 
 * @author i.gorohov
 */
interface Position {
    
    public function getTile();
    
}
