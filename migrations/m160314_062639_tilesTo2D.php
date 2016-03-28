<?php

use yii\db\Migration;

class m160314_062639_tilesTo2D extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('tiles');
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'type' => 'INTEGER(2) NOT NULL',
        ]);
        $this->createIndex('tiles_xy', 'tiles', ['x','y'], true);        
    }

    public function safeDown()
    {
        $this->dropTable('tiles');
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'z' => 'INTEGER NOT NULL',
            'type' => 'INTEGER(2) NOT NULL',
        ]);
        $this->createIndex('tiles_xyz', 'tiles', ['x','y','z'], true);
    }
}
