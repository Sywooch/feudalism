<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Castle */

$this->title = Yii::t('app', 'Feudalism') . ' — '. Yii::t('app', 'castle') . ' ' . $model->name;
?>
<div class="castle-view">

    <h1><?=Yii::t('app', 'Castle')?> «<?=Html::encode($model->name)?>»</h1>
    
    <ul>
        <li><strong><?=Yii::t('app', 'Name')?>:</strong> <?=Html::encode($model->name)?></li>
        <li><strong><?=Yii::t('app', 'Owner')?>:</strong> <?=Html::a($model->user->genderedName,['/user/view/', 'id' => $model->userId])?></li>
        <li><strong><?=Yii::t('app', 'Location')?>:</strong> —</li>
        <li><strong><?=Yii::t('app', 'Fortification')?>:</strong> <?=$model->fortification?></li>
        <li><strong><?=Yii::t('app', 'Quarters')?>:</strong> <?=$model->quarters?> (<?=Yii::t('app', '{0} used', [$model->quartersUsed])?>)</li>
    </ul>

</div>
