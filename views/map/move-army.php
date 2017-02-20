<?php

/* @var $this yii\web\View */
/* @var $army app\models\units\UnitGroup */
/* @var $user app\models\User */


$this->title = Yii::t('app','Feudalism') . ' — ' . Yii::t('app','Map');

$this->registerJsFile('/js/leaflet.js');
$this->registerCssFile('/css/leaflet.css');
$this->registerJsFile('/js/map.js?'.time());
$this->registerCssFile('/css/map.css?'.time());

$this->registerJs('mapInit(); mapInitMoveArmy('. json_encode($army->getDisplayedAttributes(true)).');');

?>
<div class="col-md-12">
    <div id="map-info-label" class="label label-default"></div>
    <div id="map" style="width: 100%; height: 400px"></div>
</div>