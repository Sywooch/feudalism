<?php

/* @var $user app\models\User */
/* @var $this yii\web\View */

use yii\helpers\Html,
    app\components\Pricelist;

$buildCastlePrice = Pricelist::get('castle', 'build');
$messageConfirm = Yii::t('app', 'You really wanna build a castle for {0,number,currency}?', [$buildCastlePrice]);

$this->title = Yii::t('app', 'Feudalism') . ' — ' . Yii::t('app','Panel');
$this->registerJs("$('#buildCastleButton').click(function(){
        var castleName = prompt('Enter new castle name');
        if (castleName && confirm('{$messageConfirm}')) {
            $.ajax({
                type: 'POST',
                url: '/castle/build',
                data: {'Castle':{'name': castleName, 'tileId': 1}},
                dataType: 'json',
                success: function (data) {
                    if (data.result === 'error') {
                        if (data.error) {
                            console.error('POST /castle/build error: ' + data.error);
                        }
                        if (data.errors) {
                            console.error('POST /castle/build errors: ');
                            console.error(data.errors);
                        }
                        alert('error');
                    } else {
                        alert('success');
                        console.log(data.result);
                    }
                },
                error: function (jqXHR, status) {
                    // error handler
                    console.error(jqXHR);
                    alert('fail' + status.code);
                }
            });
        }
    });");

?>
<header>
    <ul>
        <li><?=Html::a(Yii::t('app','Panel'), ['/'], ['class' => 'active'])?></li>
        <li><?=Html::a(Yii::t('app', 'Map'), ['/map'])?></li>
    </ul>
</header>
<h1>
    <big>Ω <?=Yii::t('app', 'Feudalism')?></big>
    <small>α</small>
</h1>
<div>
    <p><?=Yii::t('app','You are')?> <strong title="<?=Yii::t('app', '{0} lvl.', [$user->level])?>" >[<?=$user->level?>]</strong> <?=Yii::t('app','{0,select,2{Sir} 1{Lady} other{}}',[$user->gender])?> <?=$user->name?></p>
    <p><?=Yii::t('app','You have a {0,number,currency}',[$user->balance])?></p>
    <ul>
        <li><strong><?=Yii::t('app','Magic')?>: <?=$user->magic?></strong> (<?=Yii::t('app','{0} basic + {1} additional points',[$user->magicBase,$user->magic-$user->magicBase])?>)</li>
        <li><strong><?=Yii::t('app','Authority')?>: <?=$user->authority?></strong> (<?=Yii::t('app','{0} basic + {1} additional points',[$user->authorityBase,$user->authority-$user->authorityBase])?>)</li>
        <li><strong><?=Yii::t('app','Education')?>: <?=$user->education?></strong> (<?=Yii::t('app','{0} basic + {1} additional points',[$user->educationBase,$user->education-$user->educationBase])?>)</li>
        <li><strong><?=Yii::t('app','Combat')?>: <?=$user->combat?></strong> (<?=Yii::t('app','{0} basic + {1} additional points',[$user->combatBase,$user->combat-$user->combatBase])?>)</li>
    </ul>
    <button id="buildCastleButton" ><?=Yii::t('app','Build a {0,plural,=0{first} other{}} castle',[$user->getCastles()->count()])?></button>
    <ul>
        <?php foreach($user->castles as $castle): ?>
        <li><strong title="<?=Yii::t('app', '{0} fort.', [$castle->fortification])?>" >[<?=$castle->fortification?>]</strong> <?=Html::a($castle->name,['castle/view', 'id' => $castle->id])?> (<?=$castle->tileId?>)</li>
        <?php endforeach ?>
    </ul>
</div>
