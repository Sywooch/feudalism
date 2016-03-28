<?php

use yii\db\Migration;

class m160327_202700_holdings extends Migration
{
    
    public function safeUp()
    {
        $this->dropTable('castles');
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
        
        $this->dropTable('units');
        $this->createTable('units', [
            'userId' => 'INTEGER REFERENCES users(id) NOT NULL',
            'protoId' => 'INTEGER(3) NOT NULL',
            'count' => 'INTEGER NOT NULL DEFAULT 0',
            'currentGroupId' => 'INTEGER REFERENCES groups(id) DEFAULT NULL',
            'currentHoldingId' => 'INTEGER REFERENCES holdings(id) DEFAULT NULL',
            'spawned' => 'INTEGER DEFAULT NULL',
            'lastSalary' => 'INTEGER DEFAULT NULL'
        ]);
        $this->createIndex('unitsPrimaryKey', 'units', [
            'userId',
            'protoId',
            'currentGroupId',
            'currentHoldingId'
        ], true);
                
        $this->update('holdings', [
            'quartersUsed' => 0
        ]);      
        
        $this->execute('ALTER TABLE users RENAME TO tmp_users');
        $this->createTable('users', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR (255) NOT NULL',
            'gender' => 'INTEGER(1) NOT NULL DEFAULT(0)',
            'invited' => 'BOOLEAN NOT NULL DEFAULT(0)',
            'level' => 'INTEGER(3) NOT NULL DEFAULT(0)',
            'experience' => 'INTEGER NOT NULL DEFAULT(0)',
            'primaryTitleId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'balance' => 'REAL NOT NULL DEFAULT(0)',
            'magic' => 'INTEGER DEFAULT(0) NOT NULL',
            'authority' => 'INTEGER NOT NULL DEFAULT(0)',
            'education' => 'INTEGER NOT NULL DEFAULT(0)',
            'combat' => 'INTEGER NOT NULL DEFAULT(0)',
            'magicBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'authorityBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'educationBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'combatBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'currentGroupId' => 'INTEGER REFERENCES groups(id) DEFAULT NULL',
            'currentHoldingId' => 'INTEGER REFERENCES holdings(id) DEFAULT NULL',
            'capitalHoldingId' => 'INTEGER REFERENCES holdings(id) UNIQUE DEFAULT NULL',
            'registration' => 'INTEGER DEFAULT NULL',
            'lastActive' => 'INTEGER DEFAULT NULL',            
        ]);
        $this->execute('INSERT INTO users (
                            id,
                            name,
                            gender,
                            invited,
                            level,
                            experience,
                            balance,
                            magic,
                            authority,
                            education,
                            combat,
                            magicBase,
                            authorityBase,
                            educationBase,
                            combatBase,
                            currentGroupId,
                            currentHoldingId,
                            capitalHoldingId
                        ) 
                        SELECT
                                id,
                                name,
                                gender,
                                invited,
                                level,
                                experience,
                                balance,
                                magic,
                                authority,
                                education,
                                combat,
                                magicBase,
                                authorityBase,
                                educationBase,
                                combatBase,
                                currentGroupId,
                                currentCastleId,
                                capitalCastleId
                            FROM tmp_users WHERE 1;
                        ');
        $this->dropTable('tmp_users');  
    }

    public function safeDown()
    {
        $this->dropTable('holdings');
        $this->createTable('castles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'userId' => 'INTEGER REFERENCES users(id) DEFAULT NULL',
            'tileId' => 'INTEGER REFERENCES tiles(id) NOT NULL',
            'titleId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'fortification' => 'INTEGER NOT NULL DEFAULT(0)',
            'quarters' => 'INTEGER NOT NULL DEFAULT(0)',
            'quartersUsed' => 'INTEGER NOT NULL DEFAULT(0)',
            'builded' => 'INTEGER DEFAULT NULL',
            'captured' => 'INTEGER DEFAULT NULL'
        ]);
        
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
        
        $this->execute('ALTER TABLE users RENAME TO tmp_users');
        $this->createTable('users', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR (255) NOT NULL',
            'gender' => 'INTEGER(1) NOT NULL DEFAULT(0)',
            'invited' => 'BOOLEAN NOT NULL DEFAULT(0)',
            'level' => 'INTEGER(3) NOT NULL DEFAULT(0)',
            'experience' => 'INTEGER NOT NULL DEFAULT(0)',
            'primaryTitleId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'balance' => 'REAL NOT NULL DEFAULT(0)',
            'magic' => 'INTEGER DEFAULT(0) NOT NULL',
            'authority' => 'INTEGER NOT NULL DEFAULT(0)',
            'education' => 'INTEGER NOT NULL DEFAULT(0)',
            'combat' => 'INTEGER NOT NULL DEFAULT(0)',
            'magicBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'authorityBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'educationBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'combatBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'currentGroupId' => 'INTEGER REFERENCES groups(id) DEFAULT NULL',
            'currentCastleId' => 'INTEGER REFERENCES castles(id) DEFAULT NULL',
            'capitalCastleId' => 'INTEGER REFERENCES castles(id) UNIQUE DEFAULT NULL',
            'registration' => 'INTEGER DEFAULT NULL',
            'lastActive' => 'INTEGER DEFAULT NULL',            
        ]);
        $this->execute('INSERT INTO users (
                            id,
                            name,
                            gender,
                            invited,
                            level,
                            experience,
                            balance,
                            magic,
                            authority,
                            education,
                            combat,
                            magicBase,
                            authorityBase,
                            educationBase,
                            combatBase,
                            currentGroupId,
                            currentCastleId,
                            capitalCastleId
                        ) 
                        SELECT
                                id,
                                name,
                                gender,
                                invited,
                                level,
                                experience,
                                balance,
                                magic,
                                authority,
                                education,
                                combat,
                                magicBase,
                                authorityBase,
                                educationBase,
                                combatBase,
                                currentGroupId,
                                currentCastleId,
                                capitalCastleId
                            FROM tmp_users WHERE 1;
                        ');
        $this->dropTable('tmp_users');
    }
}
