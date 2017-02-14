
var popup = L.popup();
var polygons = [];

function clearPolygons() {
    while(polygon = polygons.pop()) {
        map.removeLayer(polygon);
    }
}

var polygonsLoading = false;

function loadPolygons() {
    $('#map-info-label').text('Loading tiles…').show();
    var bounds = map.getBounds();
    polygonsLoading = true;
    Request.getJson('map/get-polygons', {minLat:bounds.getSouth(),maxLat:bounds.getNorth(),minLng:bounds.getWest(),maxLng:bounds.getEast()}, function(data){
        while (tile = data.result.pop()) {
            var polygon = L.polygon(tile.coords, {
                weight: 1
            });
            polygon.addTo(map);
            polygons.push(polygon);
        }
        polygonsLoading = false;
        $('#map-info-label').hide();
    });
}

function mapInit() {
    
    resizeBlocks();

    map = L.map("map",{
        maxZoom: 19,
        minZoom: 2,
        maxBounds: new L.LatLngBounds(new L.LatLng(-90, -180), new L.LatLng(90, 180))
    }).setView([0,0], 2);
    
    var CartoDB_DarkMatterNoLabels = L.tileLayer('http://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
        subdomains: 'abcd',
        maxZoom: 19
    });
    CartoDB_DarkMatterNoLabels.addTo(map);
//    var CartoDB_DarkMatter = L.tileLayer('http://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
//        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
//        subdomains: 'abcd',
//        maxZoom: 19
//    });
//    CartoDB_DarkMatter.addTo(map);
        
    map.on("dragstart", function(e){
        clearPolygons();
        $("#map").addClass("dragging");
    });
    map.on("dragend", function(e){
        clearPolygons();
        if (map.getZoom() > 6 && !polygonsLoading) {
            loadPolygons();
        }
        setTimeout(function() {
            $("#map").removeClass("dragging");
        }, 100);
    });
    map.on("zoomstart", function(e){
        clearPolygons();
    });
    map.on("zoomend", function(e){
        clearPolygons();
        if (map.getZoom() > 6 && !polygonsLoading) {
            loadPolygons();
        } else {
            $('#map-info-label').text('Zoom in to see tiles').show();
        }
    });
    
    $('#map-info-label').text('Zoom in to see tiles').show()
    
}