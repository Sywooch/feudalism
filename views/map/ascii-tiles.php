<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm,
    yii\helpers\Html;

$this->title = Yii::t('app','Feudalism') . ' — ' . Yii::t('app','Map');

$this->registerJsFile('/js/rot.js');
$this->registerJsFile('/js/leaflet.js');
$this->registerCssFile('/css/leaflet.css');
$this->registerJsFile('/js/map.js');
$this->registerCssFile('/css/map.css');

$this->registerJs('mapInit()');

?>
<div class="col-lg-9 col-md-8">
    <div id="map" style="width: 100%; height: 400px"></div>
</div>
<div class="col-lg-3 col-md-4" id="right-panel">
    <div class="panel panel-default right-main-panel" id="tile-info" >
        <div class="panel-heading">
            <span id="tile-biomeCharacter"></span> [<span id="tile-x"></span>,<span id="tile-y"></span>] <span id="tile-biomeLabel" ></span>
        </div>
        <div class="panel-body">
            <span class="text-info"><?=Yii::t('app', 'Land owner')?>:</span>
            <div class="well-sm">
                <span id="tile-ownerName"></span>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary btn-xs right-panel-btn" id="build-castle-btn"><?=Yii::t('app', 'Build a castle')?></button>
        </div>
    </div>
    <div class="panel panel-default right-main-panel" id="holding-info">
        <div class="panel-heading">
            <span id="holding-character"></span> <span id="holding-name" ></span>
        </div>
        <div class="panel-body">
            <span class="text-info"><?=Yii::t('app', 'Holding owner')?>:</span>
            <div class="well-sm">
                <span id="holding-userName"></span>
            </div>
            <table class="table table-hover">
                <tr>
                    <td>
                        <span class="text-info">[p] <?=Yii::t('app', 'Population')?>:</span>
                    </td>
                    <td>
                        <span id="holding-population"></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-info">[f] <?=Yii::t('app', 'Fortification')?>:</span>
                    </td>
                    <td> 
                        <span id="holding-fortification"></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="text-info">[q] <?=Yii::t('app', 'Quarters')?>:</span>
                    </td>
                    <td> 
                        <span id="holding-quarters"></span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel-footer">
            <button class="btn btn-success btn-xs right-panel-btn" id="create-barony-btn"><?=Yii::t('app', 'Create a barony')?></button>
        </div>
    </div>
    <div class="label-info" id="right-bottom-label"></div>
</div>

<div class="modal" id="build-castle-modal" style="display:none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=Yii::t('app', 'Build a new castle')?></h4>
            </div>
            <?php
                /* @var $form ActiveForm */
                $form = ActiveForm::begin([
                    'id' => 'build-castle-form',
                    'options' => ['class' => 'request-form'],
                    'method' => 'post',
                    'action' => ['castle/build'],
                ]);
            ?>        
            <div class="modal-body">
                <div class="form-well">
                    <?=$form->field(new \app\models\holdings\Castle, 'name')->textInput()?>
                    <?=$form->field(new \app\models\holdings\Castle, 'tileId', ['options' => ['style' => 'display:none']])->hiddenInput(['class' => 'current-tile-id'])?>
                </div>
            </div>
            <div class="modal-footer">
                <?=Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])?>
                <?=Html::submitButton(Yii::t('app', 'Build'), ['class' => 'btn btn-primary'])?>
            </div>
            <?php $form->end() ?>
        </div>
    </div>
</div>

<div class="modal" id="create-barony-modal" style="display:none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=Yii::t('app', 'Create a barony')?></h4>
            </div>
            <?php
                /* @var $form ActiveForm */
                $form = ActiveForm::begin([
                    'id' => 'create-barony-form',
                    'options' => ['class' => 'request-form'],
                    'method' => 'post',
                    'action' => ['title/create-barony'],
                ]);
            ?>        
            <div class="modal-body">
                <div class="form-well">
                    <?=$form->field(new \app\models\titles\Barony, 'name')->textInput()?>
                    <?=Html::hiddenInput('holdingId','',['class' => 'current-holding-id'])?>
                </div>
            </div>
            <div class="modal-footer">
                <?=Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])?>
                <?=Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-primary'])?>
            </div>
            <?php $form->end() ?>
        </div>
    </div>
</div>