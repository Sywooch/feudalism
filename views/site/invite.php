<?php

/* @var $this yii\web\View */
/* @var $model app\models\InviteForm */

use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Feudalism') . ' — ' . Yii::t('app','Load invite-picture');

?>
<header>
    <div class="container">
        <h3><?=Yii::t('app','Load invite-picture')?></h3>
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($model->getErrors()): ?>
            <ul style="color:red">
                <?php foreach ($model->getErrors()['imageFile'] as $error): ?>
                <li><?=$error?></li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

                <?= $form->field($model, 'imageFile')->fileInput() ?>

                <button class="btn btn-primary"><?=Yii::t('app','Send')?></button>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>