<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title .= Yii::t('app','Map');

$this->registerJsFile('/js/rot.min.js');
$this->registerJS('
    

    resizeDisplay = function() {
        var params = display.computeSize($("#map").width(), $("#map").height());
        display.setOptions({
            width: params[0],
            height: params[1]
        });
    }

    display = new ROT.Display({forceSquareRatio:false,fontSize:18});
    resizeDisplay();

    //for (i in tiles) {
    //    display.draw(tiles[i].x,tiles[i].y,tiles[i].char,tiles[i].color,"#000");
    //}
    
for (var x = -1; x < 5; x++) {
for (var y = -1; y < 2; y++) {
    $.get("/map/chunk?x="+x+"&y="+y,
        {dataType: "json"},
        function(data) {
            for (i in data.result) {
                var tile = data.result[i];
                display.draw(tile.x+20,tile.y+10,tile.biomeCharacter,tile.biomeColor,"#000");
                if (tile.castle) {
                    display.draw(tile.x+20,tile.y+10,"Ω","#fff");
                }
                
                tilesCache[tile.x+"x"+tile.y] = tile;
            }
        }
    );
}}

    $("#map").html(display.getContainer());
    $("#map").click(function(e){
        var displayCoords = display.eventToPosition(e);
        var tileX = displayCoords[0] - 20;
        var tileY = displayCoords[1] - 10;
        var tile = tilesCache[tileX+"x"+tileY];
        display.drawText(display.getOptions().width-10,1,"["+tile.x+","+tile.y+"] "+tile.biomeLabel,10);
    });
');

?>
<h1><?=Yii::t('app','Map')?></h1>
<div id="map" style="width: 100%; height: 500px">
    
</div>
<script>
    var display, resizeDisplay, tilesCache = {};        
</script>