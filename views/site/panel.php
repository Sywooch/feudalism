<?php

/* @var $user app\models\User */
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Feudalism') . ' — ' . Yii::t('app','Panel');

$this->registerJs('$(window).resize(resizeBlocks)');

?>

<div class="container">
    <div class="row">
        <?=$this->renderFile('@app/views/map/default.php')?>
    </div>
</div>