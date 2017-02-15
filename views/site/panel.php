<?php

/* @var $user app\models\User */
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Feudalism') . ' — ' . Yii::t('app','Panel');

$this->registerJs('$(window).resize(resizeBlocks)');

?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h4 class="box-title"><?= Yii::t('app', 'Holdings') ?></h4>
                </div>
                <div class="box-body">
                <?php if (count($user->buildedHoldings)): ?>
                    <ul>
                    <?php foreach ($user->buildedHoldings as $holding): ?>
                        <li><a href="/castle/view?id=<?=$holding->id?>"><?=$holding->getFullName()?></a></li>
                    <?php endforeach ?>
                    </ul>
                <?php else: ?>
                    <p><?= Yii::t('app', 'No holdings') ?></p>
                <?php endif ?>
                </div>
                <div class="box-footer">
                    <a href="map/build-castle" class="btn btn-success"><?= Yii::t('app', 'Build new castle') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>