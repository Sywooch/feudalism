<?php

/* @var $user app\models\User */
/* @var $this yii\web\View */

use yii\helpers\Html,
    app\components\Pricelist,
    app\components\ExperienceCalculator;

$buildCastlePrice = Pricelist::get('castle', 'build');
$messageConfirm = Yii::t('app', 'You really wanna build a castle for {0,number,currency}?', [$buildCastlePrice]);

$this->title = Yii::t('app', 'Feudalism') . ' — ' . Yii::t('app','Panel');

?>

<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <?=$this->renderFile('@app/views/map/default.php')?>
        </div>
        <div class="col-lg-3">
            <h5><span class="text-info">You are:</span> [<?=$user->level?>] <?=$user->fullName?></h5>
        </div>
    </div>
</div>