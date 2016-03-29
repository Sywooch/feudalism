
function resizeBlocks() {
    var wrapperHeight = $("#wrapper").height();
    var delta = 30;
    $("#map").css("height", wrapperHeight-delta);
    $("#right-panel").css("height", wrapperHeight-delta);
}