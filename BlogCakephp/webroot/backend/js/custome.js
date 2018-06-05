$(function () {
    $('.summernote').each(function () {
        var _this = $(this);
        var progressEl = $("<progress></progress>").insertBefore(_this).hide();
        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                //var  progressEl  = _this.closest('progress');
                progressEl.show().attr({value: e.loaded, max: e.total});
                // reset progress on complete
                if (e.loaded == e.total) {
                    progressEl.hide().attr('value', '0.0');
                }
            }
        }
        _this.summernote({
            height: 240,
            toolbar: [
                ['style', ['style']],
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['table', ['table']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['picture', 'link', 'video']],
                ['view', ['fullscreen', 'codeview']],
            ],
            onChange: function (e) {
                var editor = $(this);
                _this.val(editor.code());
                _this.trigger('change');
            },
            onImageUpload: function (file, editor, welEditable) {
                data = new FormData();
                data.append("file", file[0]);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: FullPath + "admin/users/fileupload",
                    cache: false,
                    contentType: false,
                    processData: false,
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload)
                            myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                        return myXhr;
                    },
                    success: function (url) {
                        //editor.insertImage(welEditable, url);
                        $(_this).summernote('editor.insertImage', url);
                    }
                });
            }
        });
    });
});
function addLoader() {
    $(".loader-item").show();
    $("#pageloader").show();
}
function removeLoader() {
    setTimeout(function () {
        $(".loader-item").hide();
        $("#pageloader").hide();
    }, 500);

}


var formSubmitOptionsDefault = {
    beforeSubmit: beforeSubmitDefault, // pre-submit callback
    success: showSuccessDefault, // post-submit callback
    type: 'POST'        // 'get' or 'post', override for form's 'method' attribute

};

function beforeSubmitDefault(formData, jqForm, options) {
    addLoader();
}
function showSuccessDefault(responseText, statusText, xhr, $form, hideAfter) {
    removeLoader();
    if (hideAfter == undefined || hideAfter === "")
    {
        hideAfter = 10;
    }
    var $success, $error, $warning;
    if (responseText.success != undefined) {
        $success = responseText.success;
    }
    if (responseText.error != undefined) {
        $error = responseText.error;
    }
    if (responseText.warning != undefined) {
        $warning = responseText.warning;
    }
    showAlert($success, $error, $warning, hideAfter)
}

function showAlert($success, $error, $warning, hideAfter) {

    var $windowalert = 1;
    if (hideAfter == undefined || hideAfter === "")
    {
        hideAfter = 10;
    }
    if ($success != undefined && $success != '')
    {
        if ($windowalert) {
            successAlert($success, hideAfter);
        } else {
            alert($success);
        }
    }
    if ($warning != undefined && $warning != '')
    {
        if ($windowalert) {
            successAlert($warning, hideAfter);
        } else {
            alert($warning);
        }
    }
    if ($error != undefined && $error != '')
    {
        if ($windowalert) {
            successAlert($error, hideAfter);
        } else {
            alert($error);
        }
    }
}

function successAlert(msg, hideAfter) {
    if (hideAfter == undefined || hideAfter === "")
    {
        hideAfter = 10;
    }
    Messenger().post({
        hideAfter: hideAfter,
        message: msg,
        showCloseButton: true
    });
}

function errorAlert(msg, hideAfter) {
    if (hideAfter == undefined || hideAfter === "")
    {
        hideAfter = 10;
    }
    Messenger().post({
        hideAfter: hideAfter,
        message: msg,
        type: 'error',
        showCloseButton: true,
    });
}