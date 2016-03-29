<?php

/* @var $user app\models\User */
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Feudalism') . ' — ' . Yii::t('app','Panel');

$this->registerJs('$(window).resize(resizeBlocks)');

?>

<div class="container">
    <div class="row">
        <div class="col-lg-9 col-md-8">
            <?=$this->renderFile('@app/views/map/default.php')?>
        </div>
        <div class="col-lg-3 col-md-4" id="right-panel">
            <div class="right-main-panel" id="tile-info" >
                <span id="tile-info-character"></span> <span id="tile-info-label" ></span>
                <button class="btn btn-primary btn-xs"><?=Yii::t('app', 'Build a castle')?></button>
            </div>
            <div class="label-info" id="right-bottom-label"></div>
        </div>
    </div>
</div>