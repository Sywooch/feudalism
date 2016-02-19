<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Castle */

$this->title = $model->name;
?>
<div class="castle-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'userId',
            'user.name',
            'name',
            'fort',
            'lat',
            'lng',
        ],
    ]) ?>

</div>
