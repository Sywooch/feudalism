
function init() {
    requestFormsInit();
    
    $("#spinner").ajaxSend(function () {
        $(this).fadeIn("fast");
    }).ajaxStop(function () {
        $(this).fadeOut("fast");
    });
    
    prettyDates();
}


function prettyDates() {
    $('.prettyDate').each(function (idx, elem) {
        $(elem).text($.format.prettyDate(new Date($(elem).data('unixtime') * 1000)));
    });
    $('.formatDate').each(function (idx, elem) {
        $(elem).text($.format.date(new Date($(elem).data('unixtime') * 1000), 'HH:mm dd-MM-yyyy'));
    });
    $('.formatDateCustom').each(function (idx, elem) {
        $(elem).text($.format.date(new Date($(elem).data('unixtime') * 1000), $(elem).data('timeformat')));
    });
}