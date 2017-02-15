<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\Html,
    app\models\User;

/* @var $this yii\base\View */
/* @var $model User */

$form = new ActiveForm();

?>
<h1><?= Yii::t('app', 'Create your avatar:') ?></h1>
<?php $form->begin([
    'options' => [
        'id' => 'registration-form',
    ],
    'action' => Url::to(['user/create']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['user/create'])
]) ?>

<?= $form->field($model, 'name')->textInput() ?>
<?= $form->field($model, 'gender')->radioList([User::GENDER_FEMALE => Yii::t('app', 'Female'), User::GENDER_MALE => Yii::t('app', 'Male')]) ?>
<?= $form->field($model, 'magicBase')->textInput(['type' => 'number'])->label(Yii::t('app', 'Magic')) ?>
<?= $form->field($model, 'authorityBase')->textInput(['type' => 'number'])->label(Yii::t('app', 'Authority')) ?>
<?= $form->field($model, 'educationBase')->textInput(['type' => 'number'])->label(Yii::t('app', 'Education')) ?>
<?= $form->field($model, 'combatBase')->textInput(['type' => 'number'])->label(Yii::t('app', 'Combat')) ?>

<?= Html::submitButton(Yii::t('app', 'Finish registration'), ['class' => 'btn btn-primary', 'name' => 'submit', 'value' => 1]) ?>

<?php $form->end() ?>

<script type="text/javascript">
    
</script>