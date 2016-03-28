<?php

namespace app\models\holdings;

use yii\db\ActiveQuery;

class HoldingQuery extends ActiveQuery
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