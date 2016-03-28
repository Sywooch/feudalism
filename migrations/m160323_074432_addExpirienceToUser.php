<?php

use yii\db\Migration;

class m160323_074432_addExpirienceToUser extends Migration
{
    
    public function safeUp()
    {
        $this->execute('ALTER TABLE users RENAME TO tmp_users');
        $this->createTable('users', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'name' => 'VARCHAR (255) NOT NULL',
            'gender' => 'INTEGER(1) NOT NULL DEFAULT(0)',
            'invited' => 'BOOLEAN NOT NULL DEFAULT(0)',
            'level' => 'INTEGER(3) NOT NULL DEFAULT(0)',
            'experience' => 'INTEGER NOT NULL DEFAULT(0)',
            'balance' => 'REAL NOT NULL DEFAULT(0)',
            'magic' => 'INTEGER DEFAULT(0) NOT NULL',
            'authority' => 'INTEGER NOT NULL DEFAULT(0)',
            'education' => 'INTEGER NOT NULL DEFAULT(0)',
            'combat' => 'INTEGER NOT NULL DEFAULT(0)',
            'magicBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'authorityBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'educationBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'combatBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'currentGroupId' => 'INTEGER REFERENCES groups(id)',
            'currentCastleId' => 'INTEGER DEFAULT (NULL) REFERENCES castles(id)',
            'capitalCastleId' => 'INTEGER REFERENCES castles(id) UNIQUE',
        ]);
        $this->execute('INSERT INTO users (
                            id,
                            name,
                            gender,
                            invited,
                            level,
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
        $this->execute('ALTER TABLE users RENAME TO tmp_users');
        $this->createTable('users', [
            'id' => 'INTEGER PRIMARY KEY NOT NULL',
            'name' => 'VARCHAR (255) NOT NULL',
            'gender' => 'INTEGER(1) NOT NULL DEFAULT(0)',
            'invited' => 'BOOLEAN NOT NULL DEFAULT(0)',
            'level' => 'INTEGER NOT NULL DEFAULT(0)',
            'balance' => 'REAL NOT NULL DEFAULT(0)',
            'magic' => 'INTEGER DEFAULT(0) NOT NULL',
            'authority' => 'INTEGER NOT NULL DEFAULT(0)',
            'education' => 'INTEGER NOT NULL DEFAULT(0)',
            'combat' => 'INTEGER NOT NULL DEFAULT(0)',
            'magicBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'authorityBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'educationBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'combatBase' => 'INTEGER NOT NULL DEFAULT(0)',
            'currentGroupId' => 'INTEGER REFERENCES groups(id)',
            'currentCastleId' => 'INTEGER DEFAULT (NULL) REFERENCES castles(id)',
            'capitalCastleId' => 'INTEGER REFERENCES castles(id) UNIQUE',
        ]);
        $this->execute('INSERT INTO users (
                            id,
                            name,
                            gender,
                            invited,
                            level,
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
