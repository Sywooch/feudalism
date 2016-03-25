<?php

use yii\db\Migration;

class m160325_085812_addCountToUnits extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('units');
        $this->createTable('units', [
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'protoId' => 'INTEGER(3) NOT NULL',
            'count' => 'INTEGER NOT NULL DEFAULT 0',
            'currentGroupId' => 'INTEGER REFERENCES groups(id) DEFAULT NULL',
            'currentCastleId' => 'INTEGER REFERENCES castles(id) DEFAULT NULL',
            'spawned' => 'INTEGER DEFAULT NULL',
            'lastSalary' => 'INTEGER DEFAULT NULL'
        ]);
        $this->createIndex('unitsPrimaryKey', 'units', [
            'userId',
            'protoId',
            'currentGroupId',
            'currentCastleId'
        ], true);
                
        $this->update('castles', [
            'quartersUsed' => 0
        ]);        
    }

    public function safeDown()
    {
        $this->dropTable('units');
        $this->createTable('units', [
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'protoId' => 'INTEGER(3) NOT NULL',
            'currentGroupId' => 'INTEGER REFERENCES groups(id) DEFAULT NULL',
            'currentCastleId' => 'INTEGER REFERENCES castles(id) DEFAULT NULL',
            'spawned' => 'INTEGER DEFAULT NULL',
            'lastSalary' => 'INTEGER DEFAULT NULL'
        ]);
        
        $this->update('castles', [
            'quartersUsed' => 0
        ]);
    }
}
