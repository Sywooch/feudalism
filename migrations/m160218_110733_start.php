<?php

use yii\db\Migration;

class m160218_110733_start extends Migration
{
    
    public function down()
    {
        echo "m0_start cannot be reverted.\n";

        return false;
    }

    public function safeUp()
    {
        $this->execute('
            CREATE TABLE auth (
                userId   INTEGER     REFERENCES users (id) 
                                     NOT NULL,
                source   INTEGER (1) NOT NULL,
                sourceId TEXT        NOT NULL
            );
        ');
        $this->execute('
            CREATE TABLE castles (
                id     INTEGER       PRIMARY KEY
                                     NOT NULL,
                userId INTEGER       NOT NULL
                                     DEFAULT (NULL),
                name   VARCHAR (255) NOT NULL,
                fort   INTEGER       NOT NULL
                                     DEFAULT (0),
                lat    REAL          NOT NULL,
                lng    REAL          NOT NULL
            );

        ');
        $this->execute('
            CREATE TABLE groups (
                id     INTEGER       PRIMARY KEY AUTOINCREMENT
                                     NOT NULL,
                userId INTEGER       REFERENCES users (id) 
                                     NOT NULL,
                name   VARCHAR (255) DEFAULT NULL,
                lat    REAL          NOT NULL,
                lng    REAL          NOT NULL
            );

        ');
        $this->execute('
            CREATE TABLE invites (
                id     INTEGER      PRIMARY KEY AUTOINCREMENT
                                    NOT NULL,
                hash   STRING (255) UNIQUE
                                    NOT NULL,
                userId INTEGER      REFERENCES users (id) 
                                    UNIQUE
                                    DEFAULT NULL,
                time   INTEGER      DEFAULT NULL
            );

        ');
        $this->execute('
            CREATE TABLE units (
                userId          INTEGER REFERENCES users (id) 
                                        NOT NULL,
                protoId         INTEGER NOT NULL,
                currentGroupId  INTEGER REFERENCES groups (id) 
                                        DEFAULT NULL,
                currentCastleId INTEGER REFERENCES castles (id) 
                                        DEFAULT NULL
            );
        ');
        $this->execute('
            CREATE TABLE users (
                id              INTEGER       PRIMARY KEY
                                              NOT NULL,
                name            VARCHAR (255) NOT NULL,
                gender          INTEGER (1)   NOT NULL
                                              DEFAULT (0),
                invited         BOOLEAN       NOT NULL
                                              DEFAULT (0),
                level           INTEGER       NOT NULL
                                              DEFAULT (0),
                balance         REAL          NOT NULL
                                              DEFAULT (0),
                magic           INTEGER       DEFAULT (0) 
                                              NOT NULL,
                authority       INTEGER       NOT NULL
                                              DEFAULT (0),
                education       INTEGER       NOT NULL
                                              DEFAULT (0),
                combat          INTEGER       NOT NULL
                                              DEFAULT (0),
                magicBase       INTEGER       NOT NULL
                                              DEFAULT (0),
                authorityBase   INTEGER       NOT NULL
                                              DEFAULT (0),
                educationBase   INTEGER       NOT NULL
                                              DEFAULT (0),
                combatBase      INTEGER       NOT NULL
                                              DEFAULT (0),
                currentGroupId  INTEGER       REFERENCES groups (id),
                currentCastleId INTEGER       DEFAULT (NULL) 
                                              REFERENCES castles (id),
                capitalCastleId INTEGER       REFERENCES castles (id) 
                                              UNIQUE
            );
        ');
    }

}
