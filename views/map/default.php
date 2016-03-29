<?php

/* @var $this yii\web\View */

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
            <span class="text-info"><?=Yii::t('app', 'Land owner:')?></span> <span id="tile-ownerName"></span>
        </div>
    </div>
    <div class="panel panel-default right-main-panel" id="holding-info">
        <div class="panel-heading">
            <span id="holding-character"></span> <span id="holding-name" ></span>
        </div>
        <div class="panel-body">
        </div>
    </div>
    <button class="btn btn-primary btn-xs"><?=Yii::t('app', 'Build a castle')?></button>
    <div class="label-info" id="right-bottom-label"></div>
</div>