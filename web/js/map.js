function coordsTileToChunk(tileCoords)
{
    var chunkCoords = {};
    if (tileCoords.x >= 0) {
        chunkCoords.x = tileCoords.x%27;
    } else {
        chunkCoords.x = 27-Math.abs(tileCoords.x)%27;
    }
    if (tileCoords.y >= 0) {
        chunkCoords.y = tileCoords.y%15;
    } else {
        chunkCoords.y = 15-Math.abs(tileCoords.y)%15;
    }
    
    return chunkCoords;
}

function coordsChunkToTile(chunkCoords, chunkX, chunkY)
{
    var tileCoords = {};
    if (chunkCoords.x >= 0) {
        tileCoords.x = chunkCoords.x+chunkX*27;
    } else {
        tileCoords.x = 27-Math.abs(chunkCoords.x) - 27*chunkX;
    }
    if (chunkCoords.y >= 0) {
        tileCoords.y = chunkCoords.y+chunkY*15;
    } else {
        tileCoords.y = 15-Math.abs(chunkCoords.y) - 15*chunkY;
    }
    
    return tileCoords;
}

function loadChunk(ctx, x, y) {
    var display = new ROT.Display({
        forceSquareRatio: false,
        fontSize: 18,
        context: ctx,
        width: 27,
        height: 15
    });
    chunkCache[x+"x"+y] = display;
    
    $.get("/map/chunk?x="+x+"&y="+y,
        function(data) {
            for (var i in data.result) {
                var tile = data.result[i];
                var coords = coordsTileToChunk(tile);
                display.draw(coords.x,coords.y,tile.biomeCharacter,tile.biomeColor,"#000");
                if (tile.castle) {
                    display.draw(coords.x,coords.y,"Ω","#fff");
                }
                
                tilesCache[tile.x+"x"+tile.y] = tile;
            }
        }
    );   
}

var chunkCache = {}, tilesCache = {};