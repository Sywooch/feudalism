<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\Html,
    app\models\titles\Barony,
    app\models\holdings\Holding;

/* @var $this yii\base\View */
/* @var $model Barony */
/* @var $holding Holding */

$form = new ActiveForm();

?>
<h1><?= Yii::t('app', 'Create new barony of {0}:', [$holding->name]) ?></h1>
<?php $form->begin([
    'options' => [
        'id' => 'create-barony-form',
    ],
    'action' => Url::to(['title/create-barony']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['user/create-barony-form'])
]) ?>

<?= Html::hiddenInput('holdingId', $holding->id) ?>
<?= $form->field($model, 'name')->textInput() ?>

<?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary', 'name' => 'submit', 'value' => 1]) ?>

<?php $form->end() ?>

<script type="text/javascript">
    
</script>