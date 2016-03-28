<?php

use yii\db\Migration;

class m160324_092226_renameBiomeField extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('tiles');
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'biomeId' => 'INTEGER(3) NOT NULL',
            'elevation' => 'INTEGER(3) NOT NULL DEFAULT 0',
            'temperature' => 'INTEGER(2) NOT NULL DEFAULT 0',
            'rainfall' => 'INTEGER(3) NOT NULL DEFAULT 0',
            'drainage' => 'INTEGER(3) NOT NULL DEFAULT 0'
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
            'biome' => 'INTEGER(3) NOT NULL',
            'elevation' => 'INTEGER(3) NOT NULL DEFAULT 0',
            'temperature' => 'INTEGER(2) NOT NULL DEFAULT 0',
            'rainfall' => 'INTEGER(3) NOT NULL DEFAULT 0',
            'drainage' => 'INTEGER(3) NOT NULL DEFAULT 0'
        ]);
        $this->createIndex('tiles_xy', 'tiles', ['x','y'], true);
    }
}
