<?php

/* @var $this yii\base\View */
/* @var $castle app\models\holdings\Castle */

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\Html,
    app\models\units\Unit;

?>
<h1><?= $castle->getFullName() ?></h1>
<div class="row">
    <div class="col-md-6">
        <div class="box box-info">
            <table class="table table-hover" style="margin-bottom: 0">
                <tr>
                    <td class="text-info text-right" ><?=$castle->getAttributeLabel('population')?>:</td>
                    <td><?= $castle->population ?></td>
                </tr>
                <tr>
                    <td class="text-info text-right" ><?=$castle->getAttributeLabel('fortification')?>:</td>
                    <td><?= $castle->fortification ?></td>
                </tr>
                <tr>
                    <td class="text-info text-right" ><?=$castle->getAttributeLabel('quarters')?>:</td>
                    <td><?= $castle->quartersUsed ?> / <?= $castle->quarters ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-6">
    <?php if ($castle->title): ?>
        <div class="panel">
            <div class="panel-body">
                <?= $castle->title->getFullName() ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <?= Yii::t('app', 'Castle not occupied!') ?>
        </div>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $castle->buildedUserId): ?>
        <a href="title/create-barony-form?holdingId=<?= $castle->id ?>" class="btn btn-success"><?= Yii::t('app', 'Create barony') ?></a>
        <?php endif ?>
    <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
    <?php if ($castle->canSpawnUnit): ?>
        <form action="/castle/spawn-unit" method="POST" class="form-inline">

        <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken()) ?>
        <?= Html::hiddenInput('id', $castle->id) ?>
            
        <label><?= Yii::t('app', 'Spawn new unit:') ?></label>
        <?= Html::dropDownList('protoId', null, Unit::getList(), ['class' => 'form-control inline']) ?>
        <?= Html::submitButton(Yii::t('app', 'Spawn'), ['class' => 'btn btn-primary']) ?>
        
        </form>
    <?php else: ?>
        <div class="alert alert-warning">
            <?= Yii::t('app', 'You can`t spawn new unit. All quarters occupied.') ?>
        </div>
    <?php endif ?>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <h4 class="box-title"><?= Yii::t('app', 'Units') ?></h4>
            </div>
            <div class="box-body">
            <?php if (count($castle->units)): ?>
                <table class="table table-hover">
                <?php foreach($castle->units as $unit): ?>
                    <tr><td><?= $unit->getFullName() ?></td></tr>
                <?php endforeach ?>
                </table>
            <?php else: ?>
                <p><?= Yii::t('app', 'There is no units') ?></p>
            <?php endif ?>
            </div>
            <div class="box-footer">
            <?= Html::beginForm(['/castle/up-all-units'], 'post', ['class' => 'form-inline'])
            . Html::hiddenInput('id', $castle->id)
            . Html::submitButton(
                Yii::t('app','Up all units'),
                ['class' => 'btn btn-primary btn-xs'.(count($castle->units)?'':' disabled')]
            )
            . Html::endForm() ?>
            </div>
        </div>
    </div>
</div>