<?php

/* @var $this yii\web\View */
/* @var $independentTitles app\models\titles\Title[] */


$this->title = Yii::t('app','Feudalism') . ' — ' . Yii::t('app','Map');

$this->registerJsFile('/js/leaflet.js');
$this->registerCssFile('/css/leaflet.css');
$this->registerJsFile('/js/map.js?'.time());
$this->registerCssFile('/css/map.css?'.time());

$this->registerJs('mapInit(); mapInitRegions();');

?>
<div class="col-md-12">
    <div id="map-info-label" class="label label-default"></div>
    <div id="map" style="width: 100%; height: 400px"></div>
</div>
<script type="text/javascript">
    
    function mapInitRegions() {
    <?php foreach ($independentTitles as $title): ?>
        L.multiPolygon([<?= $title->getPolygon() ?>], {
            width: 1,
            color: 'white',
            fillColor: 'red'
        }).addTo(map);
    <?php endforeach ?>
    }
    
</script>
