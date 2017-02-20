<?php

namespace app\models\units;

use yii\db\ActiveQuery;

class UnitQuery extends ActiveQuery
{
    public $protoId;

    public function prepare($builder)
    {
        if ($this->protoId !== null) {
            $this->andWhere(['protoId' => $this->protoId]);
        }
        return parent::prepare($builder);
    }
}