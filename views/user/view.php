<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Feudalism') . ' — ' . $model->genderedName;
?>
<div class="user-view">

    <h1><strong title="<?=Yii::t('app', '{0} lvl.', [$model->level])?>" >[<?=$model->level?>]</strong> <?=Html::encode($model->genderedName)?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'gender',
            'invited:boolean',
            'level',
            'balance',
            'magic',
            'authority',
            'education',
            'combat',
            'magicBase',
            'authorityBase',
            'educationBase',
            'combatBase',
            'currentGroupId',
            'currentCastleId',
            'capitalCastleId',
        ],
    ]) ?>

</div>
