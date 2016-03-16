<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title .= Yii::t('app','Map');

$this->registerJsFile('/js/rot.js');
$this->registerJsFile('/js/leaflet.js');
$this->registerCssFile('/css/leaflet.css');
$this->registerJsFile('/js/map.js');
$this->registerJs('
    var map = L.map("map",{
        maxZoom: 10,
        minZoom: 10,
        zoomControl: false
    }).setView([100,-180], 10);
    

    var canvasTiles = L.tileLayer.canvas({
        continuousWorld: true,
        tileSize: 270
    });

    canvasTiles.drawTile = function(canvas, tilePoint, zoom) {
        var ctx = canvas.getContext("2d");
        canvas.setAttribute("data-x",tilePoint.x);
        canvas.setAttribute("data-y",tilePoint.y);
        loadChunk(ctx, tilePoint.x, tilePoint.y);
    };

    canvasTiles.addTo(map);
    
    $("canvas.leaflet-tile").click(function(e){
        var display = chunkCache[$(this).data("x")+"x"+$(this).data("y")];
        var displayCoords = display.eventToPosition(e);
        var realCoords = coordsChunkToTile({x:displayCoords[0],y:displayCoords[1]},$(this).data("x"),$(this).data("y"));

        var tile = tilesCache[realCoords.x+"x"+realCoords.y];
        console.log("["+tile.x+","+tile.y+"] "+tile.biomeLabel);
    });
');

?>
<h1><?=Yii::t('app','Map')?></h1>
<div id="map" style="width: 100%; height: 500px">
    
</div>