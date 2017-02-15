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
                    <p><?= Yii::t('app', 'No holdings') ?></p>
                </div>
                <div class="box-footer">
                    <a href="map/build-castle" class="btn btn-success"><?= Yii::t('app', 'Build new castle') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>