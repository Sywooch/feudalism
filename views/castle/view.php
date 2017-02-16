<?php

/* @var $this yii\base\View */
/* @var $castle app\models\holdings\Castle */

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
        <?php var_dump($castle->title) ?>
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
