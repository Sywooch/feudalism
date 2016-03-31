<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $isOwner boolean */

use yii\helpers\Html,
    app\components\ExperienceCalculator;

$this->title = Yii::t('app','Feudalism') . ' — ' . $model->fullName;

?>
<div class="col-lg-6 col-md-6">
    <h1><?=$model->fullName?></h1>
    <div class="progress" title="<?=Yii::t('app', '{0,number} XP / {1,number} for level {2}',[$model->experience, ExperienceCalculator::getExperienceByLevel($model->level+1), $model->level+1])?>" >
        <div class="progress-bar" style="width: <?=round(ExperienceCalculator::getPercentOfNextLevel($model->experience))?>%;"></div>
    </div>
</div>