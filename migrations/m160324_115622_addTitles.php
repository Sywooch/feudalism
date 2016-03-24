<?php

use yii\db\Migration;

class m160324_115622_addTitles extends Migration
{
    
    public function up()
    {
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
    }

    public function down()
    {
        $this->dropTable('titles');
    }
}
