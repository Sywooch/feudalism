<?php

use yii\db\Migration;

class m160316_130001_addDefaultInvite extends Migration
{
    public function up()
    {
        $this->insert('invites', [
            'hash' => '044c7a537e3f33b57ccbf74073bdf567'
        ]);
    }

    public function down()
    {
        $this->truncateTable('invites');
    }
}
