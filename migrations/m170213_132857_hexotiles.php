<?php

use yii\db\Migration;

class m170213_132857_hexotiles extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('tiles');
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'titleId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'centerLat' => 'REAL NOT NULL',
            'centerLng' => 'REAL NOT NULL',
        ]);
        $this->createIndex('tilesXY', 'tiles', ['x','y'], true);
        $this->createIndex('tilesLat', 'tiles', ['centerLat']);
        $this->createIndex('tilesLng', 'tiles', ['centerLng']);        
    }

    public function safeDown()
    {
        $this->dropTable('tiles');
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'titleId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'biomeId' => 'INTEGER(3) NOT NULL',
            'elevation' => 'INTEGER(3) NOT NULL DEFAULT 0',
            'temperature' => 'INTEGER(2) NOT NULL DEFAULT 0',
            'rainfall' => 'INTEGER(3) NOT NULL DEFAULT 0',
            'drainage' => 'INTEGER(3) NOT NULL DEFAULT 0'
        ]);
        $this->createIndex('tiles_xy', 'tiles', ['x','y'], true);
        
    }
    
}
