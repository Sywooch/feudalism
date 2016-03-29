
function init() {
    requestFormsInit();
    
    $("#spinner").ajaxSend(function () {
        $(this).fadeIn("fast");
    }).ajaxStop(function () {
        $(this).fadeOut("fast");
    });
}
