<?php

use yii\db\Migration;

class m170213_141646_holdings_coordinates extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('holdings');
        $this->createTable('holdings', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'INTEGER(2) NOT NULL',
            'tileId' => 'INTEGER REFERENCES tiles(id) NOT NULL',
            'lat' => 'REAL NOT NULL',
            'lng' => 'REAL NOT NULL',
            'titleId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'population' => 'INTEGER NOT NULL DEFAULT(0)',
            'fortification' => 'INTEGER NOT NULL DEFAULT(0)',
            'quarters' => 'INTEGER NOT NULL DEFAULT(0)',
            'quartersUsed' => 'INTEGER NOT NULL DEFAULT(0)',
            'builded' => 'INTEGER DEFAULT NULL',
            'buildedUserId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'captured' => 'INTEGER DEFAULT NULL'
        ]);
        $this->createIndex('holdingsProto', 'holdings', ['protoId']);
        $this->createIndex('holdingsTile', 'holdings', ['tileId'], true);
        $this->createIndex('holdingsLat', 'holdings', ['lat']);
        $this->createIndex('holdingsLng', 'holdings', ['lng']);
        $this->createIndex('holdingsTitle', 'holdings', ['titleId']);
        $this->createIndex('holdingsBuilded', 'holdings', ['builded']);
        $this->createIndex('holdingsBuildedUser', 'holdings', ['buildedUserId']);
        $this->createIndex('holdingsCaptured', 'holdings', ['captured']);
        
        $this->dropTable('unitGroups');
        $this->createTable('unitsGroups', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'tileId' => 'INTEGER REFERENCES tiles(id) NOT NULL',
            'lat' => 'REAL NOT NULL',
            'lng' => 'REAL NOT NULL',
            'name' => 'VARCHAR(255) DEFAULT NULL',
        ]);
        $this->createIndex('unitsGroupsUser', 'unitsGroups', ['userId']);
        $this->createIndex('unitsGroupsTile', 'unitsGroups', ['tileId']);
        $this->createIndex('unitsGroupsLat', 'unitsGroups', ['lat']);
        $this->createIndex('unitsGroupsLng', 'unitsGroups', ['lng']);
    }

    public function safeDown()
    {
        $this->dropTable('holdings');
        $this->createTable('holdings', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'protoId' => 'INTEGER(2) NOT NULL',
            'tileId' => 'INTEGER REFERENCES tiles(id) NOT NULL',
            'titleId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'population' => 'INTEGER NOT NULL DEFAULT(0)',
            'fortification' => 'INTEGER NOT NULL DEFAULT(0)',
            'quarters' => 'INTEGER NOT NULL DEFAULT(0)',
            'quartersUsed' => 'INTEGER NOT NULL DEFAULT(0)',
            'builded' => 'INTEGER DEFAULT NULL',
            'buildedUserId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'captured' => 'INTEGER DEFAULT NULL'
        ]);
        $this->createIndex('tileId', 'holdings', ['tileId'], true);
        
        $this->dropTable('unitsGroups');
        $this->createTable('unitGroups', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'tileId' => 'INTEGER REFERENCES tiles(id) NOT NULL',
            'name' => 'VARCHAR(255) DEFAULT NULL',
        ]);
    }
    
}
