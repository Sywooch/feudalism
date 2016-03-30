<?php

use yii\authclient\widgets\AuthChoice;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Feudalism');

?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1>
                <big>Ω <?=Yii::t('app', 'Feudalism')?></big>
                <small>α</small>
            </h1>
            <?=AuthChoice::widget([
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => false
            ])?>
        </div>
    </div>
</div>