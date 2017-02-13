
var popup = L.popup();

function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("You clicked the map at " + e.latlng.toString())
        .openOn(map);
}

function mapInit() {
    
    resizeBlocks();

    map = L.map("map",{
        maxZoom: 18,
        minZoom: 2,
        maxBounds: new L.LatLngBounds(new L.LatLng(-180, -180), new L.LatLng(180, 180))
    }).setView([0,0], 1);
    
    var Thunderforest_Pioneer = L.tileLayer('http://{s}.tile.thunderforest.com/pioneer/{z}/{x}/{y}.png?apikey={apikey}', {
        attribution: '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        apikey: 'c80873320ccb47a98d3a671d6a2f9040'
    });
    Thunderforest_Pioneer.addTo(map);
        
    map.on("dragstart", function(e) {
        $("#map").addClass("dragging");
    });
    map.on("dragend", function (e) {
        setTimeout(function() {
            $("#map").removeClass("dragging");
        }, 100);
    });
    
    map.on('click', onMapClick);
    
}