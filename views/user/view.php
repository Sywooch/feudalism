<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $isOwner boolean */

use app\components\ExperienceCalculator,
    yii\helpers\Html;

$this->title = Yii::t('app','Feudalism') . ' — ' . $model->fullName;
var_dump(date('d-m-Y', $model->registration));
?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <h1><?=$model->fullName?></h1>
            <div class="progress" >
                <span class="label"><?= Yii::t('app', '{0,number} / {1,number} XP for level {2}',[$model->experience, ExperienceCalculator::getExperienceByLevel($model->level+1), $model->level+1]) ?></span>
                <div class="progress-bar" style="width: <?=round(ExperienceCalculator::getPercentOfNextLevel($model->experience))?>%;"></div>
            </div>
            <div class="well">
            <?php if ($model->primaryTitleId): ?>
                <h5><?=$model->getAttributeLabel('primaryTitle')?>:</h5>
                <div class="text-primary">
                    [<?=$model->primaryTitle->level?>] <?=$model->primaryTitle->fullName?>
                </div>
                
                <?php if ($model->primaryTitle->suzerainId): ?>
                <h5><?=$model->primaryTitle->getAttributeLabel('suzerain')?>:</h5>
                <div class="text-info">
                    <?=$model->primaryTitle->suzerain->userName?>
                </div>
                <?php else: ?>
                <div class="text-info">
                    <?=Yii::t('app','Independent ruler')?>
                </div>
                <?php endif?>
            <?php endif?>
            </div>
            <div class="col-lg-6 col-md-6 well">
                <table class="table table-hover" style="margin-bottom: 0">
                    <tr>
                        <td class="text-info text-right" ><?=$model->getAttributeLabel('balance')?>:</td>
                        <td><?=$model->balance?></td>
                    </tr>
                    <tr>
                        <td class="text-info text-right" ><?=$model->getAttributeLabel('magic')?>:</td>
                        <td><?=$model->magic?> (<?=$model->magicBase?> + <?=$model->magic - $model->magicBase?>)</td>
                    </tr>
                    <tr>
                        <td class="text-info text-right" ><?=$model->getAttributeLabel('authority')?>:</td>
                        <td><?=$model->authority?> (<?=$model->authorityBase?> + <?=$model->authority - $model->authorityBase?>)</td>
                    </tr>
                    <tr>
                        <td class="text-info text-right" ><?=$model->getAttributeLabel('education')?>:</td>
                        <td><?=$model->education?> (<?=$model->educationBase?> + <?=$model->education - $model->educationBase?>)</td>
                    </tr>
                    <tr>
                        <td class="text-info text-right" ><?=$model->getAttributeLabel('combat')?>:</td>
                        <td><?=$model->combat?> (<?=$model->combatBase?> + <?=$model->combat - $model->combatBase?>)</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6 col-md-6 well">
                <h4><?=Yii::t('app', 'Current traits')?>:</h4>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <h3>Titles:</h3>
            <?php if (count($model->titles)): ?>
            <?php foreach ($model->titles as $title): ?>
            <div class="panel <?=$title->isPrimaryTitle($model) ? 'panel-info' : 'panel-default'?>">
                    <div class="panel-heading">
                        [<?=$title->level?>] <?=$title->fullName?>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <tr>
                                <td style="width:50%" class="text-info text-right"><?=Yii::t('app','Title captured')?>:</td>
                                <td class="prettyDate" data-unixtime="<?=$title->captured?>" ><?=date("d-m-Y", $title->captured)?></td>
                            </tr>
                        </table>
                        <?php if (count($title->holdings)): ?>
                        <h4><?=Yii::t('app', 'Holdings')?></h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>[p]</th>
                                    <th>[f]</th>
                                    <th>[q]</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($title->holdings as $holding): ?>
                                <tr>
                                    <td><a href="/castle?id=<?= $holding->id ?>"><?=$holding->fullName?></a></td>
                                    <td><?=$holding->population?></td>
                                    <td><?=$holding->fortification?></td>
                                    <td><?=$isOwner ? $holding->quartersUsed . '/' . $holding->quarters : $holding->quarters?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                        <?php endif ?>
                    </div>
                <div class="panel-footer">
                    <?= Html::beginForm(['/title/destroy'], 'post')
                        . Html::hiddenInput('id', $title->id)
                        . Html::submitButton(
                            Yii::t('app','Destroy title'),
                            ['class' => 'btn btn-danger btn-xs']
                        )
                        . Html::endForm() ?>
                </div>
            </div>
            <?php endforeach ?>
            <?php else: ?>
            <div class="label-warning">
                <?=Yii::t('app', 'You have no one titular title')?>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>