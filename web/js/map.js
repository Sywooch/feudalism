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

var chunkCache = {}, tilesCache = {};
var ROT_OPTIONS = {
    forceSquareRatio: false,
    fontSize: 20,
    fontFamily: 'pt_monoregular',
    width: 25,
    height: 15
};

function showTileInfo(tile) {
    $('.right-main-panel').hide();
    $('#tile-info-character').text(tile.biomeCharacter);
    $('#tile-info-character').css('color', tile.biomeColor);
    $('#tile-info-label').text(tile.biomeLabel);
    $('#tile-info-label').css('color', tile.biomeColor);
    $('#tile-info').show();
}