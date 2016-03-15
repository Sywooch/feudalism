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

    $(function() {

        display = new ROT.Display({forceSquareRatio:false,fontSize:18});
        resizeDisplay();

        for (i in tiles) {
            display.draw(tiles[i].x,tiles[i].y,tiles[i].char,tiles[i].color,"#000");
        }


        $("#map").html(display.getContainer());
    });
');

?>
<h1><?=Yii::t('app','Map')?></h1>
<div id="map" style="width: 100%; height: 500px">
    
</div>
<script>
    var map, display, resizeDisplay;

    var tiles = [];
    <?php foreach (app\models\Tile::find()->all() as $tile): ?>
    
        tiles.push({
            'x': <?=$tile->x+20?>,
            'y': <?=$tile->y+10?>,
            'char': '<?=$tile->biomeCharacter?>',
            'color': '<?=$tile->biomeColor?>'
        });
    
    <?php endforeach ?>
        
</script>