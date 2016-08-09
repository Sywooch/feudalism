<?php

use yii\helpers\Html;

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
            <?=Html::a(Yii::t('app','Login with Google'), ['site/auth', 'authclient' => 'google'], ['class' => 'btn btn-lg btn-primary'])?>
        </div>
    </div>
</div>