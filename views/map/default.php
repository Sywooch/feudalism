<?php

/* @var $this yii\web\View */


$this->title = Yii::t('app','Feudalism') . ' — ' . Yii::t('app','Map');

$this->registerJsFile('/js/leaflet.js');
$this->registerCssFile('/css/leaflet.css');
$this->registerJsFile('/js/map.js?'.time());
$this->registerCssFile('/css/map.css');

$this->registerJs('mapInit()');

?>
<div class="col-lg-12 col-md-12">
    <div id="map" style="width: 100%; height: 400px"></div>
</div>
