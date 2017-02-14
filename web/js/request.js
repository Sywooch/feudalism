
var Request = (function() {
    function make(url, data, dataType, method, callback) {
        data._csrf = yii.getCsrfToken();
        $.ajax({
            type: method,
            url: url,
            data: data,
            dataType: dataType,
            success: function (data) {
                if (typeof data === 'object') {
                    if (data.result === 'error') {
                        if (data.error) {
                            console.error(method+' '+url+' error: ' + data.error);
                        }
                        if (data.errors) {
                            console.error(method+' '+url+' errors: ');
                            console.error(data.errors);
                        }
                        return;
                    }
                }
                callback(data);
            },
            error: function (jqXHR, status) {
                console.error(method+' '+url+' error #'+status);
                console.error(jqXHR);
            }
        });
    }

    function makeJson(url, data, method, callback) {
        return make(url, data, 'json', method, callback);
    }

    function makeHtml(url, data, method, callback) {
        return make(url, data, 'text', method, callback);
    }

    function getJson(url, data, callback) {
        return makeJson(url, data, 'GET', callback);
    }

    function postJson(url, data, callback) {
        return makeJson(url, data, 'POST', callback);
    }

    function getHtml(url, data, callback) {
        return makeHtml(url, data, 'GET', callback);
    }

    function postHtml(url, data, callback) {
        return makeHtml(url, data, 'POST', callback);
    }
    
    return {
        getJson: getJson,
        postJson: postJson,
        getHtml: getHtml,
        postHtml: postHtml
    };
})();

function requestFormsInit() {
    $(document).on("submit", ".request-form", function(){
        var callback = $(this).data('callback') || defaultRequestJsonCallback;
        Request.postJson(
            $(this).attr('action'),
            $(this).serialize(),
            callback
        );
        return false;
    });
}

function defaultRequestJsonCallback(data) {
    alert("ok");
}