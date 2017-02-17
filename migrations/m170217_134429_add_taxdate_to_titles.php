<?php

use yii\db\Migration;

class m170217_134429_add_taxdate_to_titles extends Migration
{
    
    public function safeUp()
    {
        
        $this->renameTable('titles', 'tmpTitles');
        $this->createTable('titles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'level' => 'INTEGER(1) NOT NULL', // 0 => баронство, 1 => графство, 2 => герцогство, 3 => королевство, 4 => Империя
            'userId' => 'INTEGER REFERENCES users(id) DEFAULT NULL',
            'suzerainId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'created' => 'INTEGER NOT NULL',
            'createdByUserId' => 'INTEGER REFERENCES users(id) DEFAULT NULL',
            'captured' => 'INTEGER DEFAULT NULL',
            'lastTaxrent' => 'INTEGER DEFAULT NULL',
        ]);
        $this->execute('INSERT INTO titles (
                            id,
                            name,
                            level,
                            userId,
                            suzerainId,
                            created,
                            createdByUserId,
                            captured
                        ) 
                        SELECT
                            id,
                            name,
                            level,
                            userId,
                            suzerainId,
                            created,
                            createdByUserId,
                            captured
                        FROM tmpTitles WHERE 1;
                        ');
        $this->dropTable('tmpTitles');
        
    }

    public function safeDown()
    {
        
        $this->renameTable('titles', 'tmpTitles');
        $this->createTable('titles', [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
            'name' => 'VARCHAR(255) NOT NULL',
            'level' => 'INTEGER(1) NOT NULL', // 0 => баронство, 1 => графство, 2 => герцогство, 3 => королевство, 4 => Империя
            'userId' => 'INTEGER REFERENCES users(id) DEFAULT NULL',
            'suzerainId' => 'INTEGER REFERENCES titles(id) DEFAULT NULL',
            'created' => 'INTEGER NOT NULL',
            'createdByUserId' => 'INTEGER REFERENCES users(id) DEFAULT NULL',
            'captured' => 'INTEGER DEFAULT NULL'
        ]);
        $this->execute('INSERT INTO titles (
                            id,
                            name,
                            level,
                            userId,
                            suzerainId,
                            created,
                            createdByUserId,
                            captured
                        ) 
                        SELECT
                            id,
                            name,
                            level,
                            userId,
                            suzerainId,
                            created,
                            createdByUserId,
                            captured
                        FROM tmpTitles WHERE 1;
                        ');
        $this->dropTable('tmpTitles');
        
    }
    
}
