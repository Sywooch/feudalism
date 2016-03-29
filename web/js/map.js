function coordsTileToChunk(tileCoords)
{
    var chunkCoords = {};
    if (tileCoords.x >= 0) {
        chunkCoords.x = tileCoords.x%25;
    } else {
        chunkCoords.x = (Math.abs(tileCoords.x)%25) ? 25-Math.abs(tileCoords.x)%25 : 0;
    }
    if (tileCoords.y >= 0) {
        chunkCoords.y = tileCoords.y%15;
    } else {
        chunkCoords.y = (Math.abs(tileCoords.y)%15) ? 15-Math.abs(tileCoords.y)%15 : 0;
    }
    return chunkCoords;
}

function coordsChunkToTile(chunkCoords, chunkX, chunkY)
{
    return {
        'x': chunkCoords.x+chunkX*25,
        'y': chunkCoords.y+chunkY*15
    };
}

function loadChunk(ctx, x, y) {
    var options = $.extend({context: ctx}, ROT_OPTIONS);
    var display = new ROT.Display(options);
    chunkCache[x+"x"+y] = display;
    
    $.get("/map/chunk?x="+x+"&y="+y,
        function(data) {
            for (var i = 0; i < data.result.length; i++) {
                var tile = data.result[i];
                var coords = coordsTileToChunk(tile);
                display.draw(coords.x,coords.y,tile.biomeCharacter,tile.biomeColor,"#000");
                if (tile.holding) {
                    display.draw(coords.x,coords.y,"Ω","#fff", "#000");
                }
                
                tilesCache[tile.x+"x"+tile.y] = tile;
            }
        }
    );   
}

var chunkCache = {}, tilesCache = {}, map;
var ROT_OPTIONS = {
    forceSquareRatio: false,
    fontSize: 20,
    fontFamily: 'pt_monoregular',
    width: 25,
    height: 15
};

function showInfo(type, data, hideAll) {
    hideAll = !!hideAll;
    
    if (hideAll) {        
        $('.right-main-panel').hide();
    }
    console.log(data);
    $block = $('#'+type+'-info');
    
    for (var i in data) {
        if (data[i] && typeof data[i] !== 'function' && typeof data[i] !== 'object') {
            $('#'+type+'-'+i).text(data[i]);
        }
    }
    
    $block.show();
}

function showTileInfo(tile) {
    showInfo('tile', tile, true);
    $('#tile-info').children('.panel-heading').css('color', tile.biomeColor);
    if (tile.holding) {
        showInfo('holding', tile.holding);
    }
}

function mapInit() {
    
    resizeBlocks();

    map = L.map("map",{
        maxZoom: 10,
        minZoom: 10,
        zoomControl: false
    }).setView([100,-180], 10);
    
    var canvasTiles = L.tileLayer.canvas({
        continuousWorld: true,
        tileSize: 300
    });

    canvasTiles.drawTile = function(canvas, tilePoint, zoom) {
        var ctx = canvas.getContext("2d");
        canvas.setAttribute("data-x",tilePoint.x);
        canvas.setAttribute("data-y",tilePoint.y);
        loadChunk(ctx, tilePoint.x, tilePoint.y);
    };

    canvasTiles.addTo(map);
    
    map.on("dragstart", function(e) {
        $("#map").addClass("dragging");
    });
    map.on("dragend", function (e) {
        setTimeout(function() {
            $("#map").removeClass("dragging");
        }, 100);
    });
    
    $("#map").on("mousemove", "canvas.leaflet-tile", function(e){
        $("#right-bottom-label").empty();
        if (!$("#map").hasClass("dragging")) { // проверка, что это не перетаскивание
            var tile = getTileByEvent($(this).data("x"), $(this).data("y"), e);
            if (tile) {
                $("#right-bottom-label").text(tile.id+" ["+tile.x+","+tile.y+"] "+tile.biomeLabel);
                if (tile.holding) {
                    $("#right-bottom-label").append(tile.holding.name);
                }
            }
        }
    });
    
    $("#map").on("click", "canvas.leaflet-tile", function(e){
        if (!$("#map").hasClass("dragging")) { // проверка, что это клик, а не конец перетаскивания
            var tile = getTileByEvent($(this).data("x"), $(this).data("y"), e);
            if (tile) {
                showTileInfo(tile);
            }
        }
    });
    
    $("#map").on("mouseover", "canvas.leaflet-tile", function(e){
        $("#right-bottom-label").empty();
    });
}

function getTileByEvent(chunkX, chunkY, e){
    var display = chunkCache[chunkX+"x"+chunkY];
    var displayCoords = display.eventToPosition(e);
    var realCoords = coordsChunkToTile({x:displayCoords[0],y:displayCoords[1]},chunkX,chunkY);
    return tilesCache[realCoords.x+"x"+realCoords.y];
}