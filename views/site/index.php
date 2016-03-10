<?php

use yii\authclient\widgets\AuthChoice;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Feudalism');

?>
<h1>
    <img src="/img/logo64.png" alt="">
    <big><?=Yii::t('app', 'Feudalism')?></big>
    <small><em>α</em></small>
</h1>
<?=AuthChoice::widget(['baseAuthUrl' => ['site/auth']])?>