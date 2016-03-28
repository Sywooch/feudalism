<?php

namespace app\models\titles;

use yii\db\ActiveQuery;

class TitleQuery extends ActiveQuery
{
    public $level;

    public function prepare($builder)
    {
        if ($this->level !== null) {
            $this->andWhere(['level' => $this->level]);
        }
        return parent::prepare($builder);
    }
}