<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\Html,
    app\models\holdings\Castle;

/* @var $this yii\base\View */
/* @var $model Castle */

$form = new ActiveForm();

?>
<?php $form->begin([
    'options' => [
        'id' => 'build-castle-form',
    ],
    'action' => Url::to(['castle/build']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['castle/build-form', 'tileId' => $model->tileId])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'tileId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'buildedUserId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?= $form->field($model, 'name')->textInput() ?>

<?= Html::submitButton(Yii::t('app', 'Build a castle'), ['class' => 'btn btn-primary']) ?>

<?php $form->end() ?>

<script type="text/javascript">
    
</script>