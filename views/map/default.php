<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app','Feudalism') . ' — ' . Yii::t('app','Map');

$this->registerJsFile('/js/rot.js');
$this->registerJsFile('/js/leaflet.js');
$this->registerCssFile('/css/leaflet.css');
$this->registerJsFile('/js/map.js');

$this->registerJs('mapInit()');

?>
<div id="map" style="width: 100%; height: 400px"></div>
