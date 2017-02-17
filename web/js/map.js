
var popup = L.popup();
var polygons = [];
var markers = [];

function clearPolygons() {
    while(polygon = polygons.pop()) {
        map.removeLayer(polygon);
    }
}

function clearMarkers() {
    while(marker = markers.pop()) {
        map.removeLayer(marker);
    }
}

var cityIcon = L.icon({
    iconUrl: '/img/city.png',
    iconRetinaUrl: '/img/city@x2.png',
    iconSize: [32, 32],
});

var castleIcon = L.icon({
    iconUrl: '/img/castle.png',
    iconRetinaUrl: '/img/castle@x2.png',
    iconSize: [32, 32],
});

var armyIcon = L.icon({
    iconUrl: '/img/army.png',
    iconRetinaUrl: '/img/army@x2.png',
    iconSize: [32, 32],
});

var currentRequestPolygons = false;
var currentRequestMarkers = false;

function loadPolygons() {
    $('#map-info-label').text('Loading tiles…').show();
    var bounds = map.getBounds();
    if (currentRequestPolygons) {
        currentRequestPolygons.abort();
    }
    currentRequestPolygons = Request.getJson('/map/get-polygons', {
        minLat: bounds.getSouth()-0.1,
        maxLat: bounds.getNorth()+0.1,
        minLng: bounds.getWest()-0.1,
        maxLng: bounds.getEast()+0.1
    }, function(data){
        while (tile = data.result.pop()) {
            var polygon = L.polygon(tile.coords, {
                weight: 1,
                color: 'white',
                fillColor: tile.occupied ? 'red' : 'white',
                fillOpacity: 0.2
            });
            polygon.id = tile.id;
            polygon.center = new L.LatLng(tile.centerLat, tile.centerLng);
            polygon.occupied = tile.occupied;
            polygon.on("click", function(e){
                if (!e.target.occupied) {
                    popup.setLatLng(e.target.center).setContent('<a href="/castle/build-form?tileId='+e.target.id+'" class="btn btn-primary" >Build castle here</a>').openOn(map);
                }
            });
            polygon.addTo(map);
            polygons.push(polygon);
        }
        currentRequestPolygons = false;
        $('#map-info-label').hide();
    });
}

function loadMarkers() {
    $('#map-info-label').text('Loading holdings…').show();
    var bounds = map.getBounds();
    if (currentRequestMarkers) {
        currentRequestMarkers.abort();
    }
    currentRequestMarkers = Request.getJson('/map/get-holdings', {
        minLat: bounds.getSouth()-0.1,
        maxLat: bounds.getNorth()+0.1,
        minLng: bounds.getWest()-0.1,
        maxLng: bounds.getEast()+0.1
    }, function(data){
        var holdings = data.result.holdings,
            armies = data.result.armies;
        var holding,army;
        while (holding = holdings.pop()) {
            var marker = L.marker(holding.coords, {
                icon: holding.protoId == 1 ? castleIcon : cityIcon
            });
            marker.id = holding.id;
            marker.type = 'holding';
            marker.bindPopup('<h5>'+holding.name+'</h5><a href="/castle?id='+holding.id+'" class="btn btn-info">[i] View info</a>');
            marker.addTo(map);
            markers.push(marker);
        }
        while (army = armies.pop()) {
            var marker = L.marker(army.coords, {
                icon: armyIcon
            });
            marker.id = army.id;
            marker.type = 'army';
            marker.bindPopup('<h5>'+army.name+'</h5>');
            marker.addTo(map);
            markers.push(marker);
        }
        currentRequestMarkers = false;
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
        $("#map").addClass("dragging");
        clearMarkers();
    });
    map.on("dragend", function(e){
        setTimeout(function() {
            $("#map").removeClass("dragging");
        }, 100);
        clearMarkers();
        if (map.getZoom() > 4) {
            loadMarkers();
        }
    });
    
    map.on("zoomstart", function(e){
        clearMarkers();
    });
    map.on("zoomend", function(e){
        clearMarkers();
        if (map.getZoom() > 4) {
            loadMarkers();
        } else {
            $('#map-info-label').text('Zoom in to see holdings').show();
        }
    });
    
    $('#map-info-label').text('Zoom in to see holdings').show();
}

function mapInitBuildingSelect(){
    
    map.on("dragstart", function(e){
        clearPolygons();
    });
    map.on("dragend", function(e){
        clearPolygons();
        if (map.getZoom() > 7) {
            loadPolygons();
        }
    });
    
    map.on("zoomstart", function(e){
        clearPolygons();
    });
    map.on("zoomend", function(e){
        clearPolygons();
        if (map.getZoom() > 7) {
            loadPolygons();
        } else {
            $('#map-info-label').text('Zoom in to see tiles').show();
        }
    });
    
    $('#map-info-label').text('Zoom in to see tiles').show();
    
}