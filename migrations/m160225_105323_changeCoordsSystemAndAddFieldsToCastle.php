<?php

use yii\db\Migration;

class m160225_105323_changeCoordsSystemAndAddFieldsToCastle extends Migration
{
    public function safeUp()
    {
        
        $this->createTable('tiles', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'x' => 'INTEGER NOT NULL',
            'y' => 'INTEGER NOT NULL',
            'z' => 'INTEGER NOT NULL',
            'type' => 'INTEGER(2) NOT NULL',
        ]);
        $this->createIndex('tiles_xyz', 'tiles', ['x','y','z'], true);
        
        $this->dropTable('groups');
        $this->createTable('unitGroups', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'tileId' => 'INTEGER REFERENCES tiles(id) NOT NULL',
            'name' => 'VARCHAR(255) DEFAULT NULL',
        ]);
        
        $this->dropTable('castles');
        $this->createTable('castles', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL DEFAULT(NULL)',
            'tileId' => 'INTEGER REFERENCES tiles(id) NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'fortification' => 'INTEGER NOT NULL DEFAULT(0)',
            'quarters' => 'INTEGER NOT NULL DEFAULT(0)',
            'quartersUsed' => 'INTEGER NOT NULL DEFAULT(0)',
        ]);
        
    }

    public function safeDown()
    {
        $this->dropTable('tiles');
        
        $this->dropTable('unitGroups');
        $this->createTable('groups', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'INTEGER REFERENCES users (id) NOT NULL',
            'name' => 'VARCHAR(255) DEFAULT NULL',
            'lat' => 'REAL NOT NULL',
            'lng' => 'REAL NOT NULL',
        ]);
        
        $this->dropTable('castles');
        $this->createTable('castles', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL DEFAULT (NULL)',
            'name' => 'VARCHAR(255) NOT NULL',
            'fort' => 'INTEGER NOT NULL DEFAULT (0)',
            'lat' => 'REAL NOT NULL',
            'lng' => 'REAL NOT NULL'
        ]);
        
    }
}
