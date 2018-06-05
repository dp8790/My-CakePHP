var FORM_SUBMIT_MODE = 'ajax';
var LINK_MODE = 'ajax';

var formSubmitOptionsDefault = {
    beforeSubmit: beforeSubmitDefault, // pre-submit callback
    success: showSuccessDefault, // post-submit callback
    // other available options:
    //url:       url         // override for form's 'action' attribute
    type: 'POST'        // 'get' or 'post', override for form's 'method' attribute
            //dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type)
            //clearForm: true        // clear all form fields after successful submit
            //resetForm: true        // reset the form after successful submit
            // $.ajax options can be used here too, for example:
            //timeout:   3000
};


Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
    theme: 'flat'
}

function addLoader() {
    $("html").addClass("loadstate");
}
function removeLoader() {
    setTimeout(function () {
        $("html").removeClass("loadstate");
    }, 500);
}
function beforeSubmitDefault(formData, jqForm, options) {
    addLoader();
}

function showSuccessDefault(responseText, statusText, xhr, $form, $success, $error, $warning) {
    removeLoader();
    // if($success != undefined && $success != '')
    // {
    // successAlert($success);
    // }
    // if($warning != undefined && $warning != '')
    // {
    // successAlert($warning);
    // }
    // if($error != undefined && $error != '')
    // {
    // errorAlert($error);
    // }

}
function successAlert($msg) {
    if ($msg != undefined && $msg != '')
    {
        alert($msg);
    }
}
function errorAlert($msg) {

    if ($msg != undefined && $msg != '')
    {
        alert($msg);
    }
}
// function errorAlert($msg) {

// if($msg != undefined && $msg != '')
// {
// alert($msg);
// }
// }
function addEditFormSubmitHandler(form) {
    
    var currentForm = $(form);
    var currentFormSubmitOptions = formSubmitOptionsDefault;
    currentFormSubmitOptions.dataType = 'json';

    currentFormSubmitOptions.success = function (responseText, statusText, xhr, $form) {
        $status = $success = $error = $warning = '';
        if (responseText.status != undefined) {
            $status = responseText.status;
        }
        checkAlert(responseText);
        if ($status == '1') {
            var form_hidden = $("#form-hidden", $form);
            var next_action = form_hidden.attr('next_action');

            callAjaxLinkHtml(next_action);

        }
    }
    if (FORM_SUBMIT_MODE == 'ajax') {
        currentForm.ajaxSubmit(currentFormSubmitOptions);
    } else {
        form.submit();
    }

}
function addNewSubmitHandler(form) {

    var currentForm = $(form);
    var currentFormSubmitOptions = formSubmitOptionsDefault;
    currentFormSubmitOptions.dataType = 'json';

    currentFormSubmitOptions.success = function (responseText, statusText, xhr, $form) {

        $status = $success = $error = $warning = '';
        if (responseText.status != undefined) {
            $status = responseText.status;
        }
        checkAlert(responseText);

        var modalEl = $($form).parents('.modal');
        modalEl.modal('hide');
        var new_modal = modalEl.attr('new_modal');

        var selElId = '#' + $(modalEl).attr('popupel');
        var selEl = $(selElId);
        if (selEl.length <= 0) {
            var idEl = $(modalEl).prop('id');
            idEl = idEl.replace("modal_", "");
            selElId = '#' + idEl;
            selEl = $(selElId);
        }
        if (selEl.length <= 0) {
            selEl = $('select[new_modal="' + new_modal + '"]').get(0);
        }
        if (selEl.length >= 1) {
            var otherattr = null;
            addOption(selEl, responseText.data.id, responseText.data.name, otherattr);
            $(selEl).trigger('change');
        }

    }

    currentForm.ajaxSubmit(currentFormSubmitOptions);

}

function resetForm($form) {

    if ($form.hasClass("noreset")) {
        return false;
    }

    $form.find('input:text, input[type="number"], input[type="hidden"], input:password, input:file, select, textarea').not('[name="_method"]').val('');
    $form.find('input:radio, input:checkbox').not('[name="_method"]').removeAttr('checked').removeAttr('selected');
}
function setModalEvent() {
    // Focus first input when form modal is shown
    $('.modal').on('shown.bs.modal', function (e) {
        resetForm($("form", this));
        var timer = window.setTimeout(function () {
            $('input:visible,select:visible,textarea:visible', this).first().focus();
            typeof timer !== 'undefined' && window.clearTimeout(timer);
        }.bind(this), 10);

    });

    $('.modal').on('hidden.bs.modal', function (e) {
        var modalEl = $(this);
        var new_modal = modalEl.attr('new_modal');
        var selElId = '#' + $(modalEl).attr('popupel');
        var selEl = $(selElId);

        if (selEl != undefined && selEl.length <= 0) {
            var idEl = $(modalEl).prop('id');
            idEl = idEl.replace("modal_", "");
            selElId = '#' + idEl;
            selEl = $(selElId);
        }
        if (selEl != undefined && selEl.length <= 0) {
            selEl = $('select[new_modal="' + new_modal + '"]').get(0);
        }
        if (selEl != undefined && selEl.length >= 1) {
            var val = selSelectedValues(selEl);
            val = val.split(",");
            var found = ($.inArray("new", val) >= 0) ? true : false;
            var foundNewHide = ($.inArray("newhide", val) >= 0) ? true : false;

            if (found || foundNewHide) {
                $(selElId + " option[value='new']").prop('selected', false);
                $(selElId + " option[value='newhide']").prop('selected', false);
                if (selEl.prop('multiple')) {
                    $(selElId + " option[value='']").prop('selected', false);
                } else {
                    $(selElId + " option[value='']").prop('selected', true);
                }
            }
        }
    });
}

function addOption(selEl, val, name, otherattr) {
    selEl = $(selEl).get(0);
    var array = [
        selEl
    ];
    var new_modal = $(selEl).attr('new_modal');
    $.each($('select[new_modal="' + new_modal + '"]'), function (i, v) {//.not(selEl)
        array.push($(this).get(0));
    });


    if (otherattr == undefined || typeof otherattr === 'string' || typeof otherattr !== 'object' || otherattr.length <= 0) {
        otherattr = [
        ];
    }


    $.each(array, function (i, v) {
        var _this = $(this);
        var oEl = $('option[value="' + val + '"]', _this);
        var len = oEl.length;
        if (len <= 0) {
            var option = $('<option>', {
                value: val,
                text: name,
                // selected: 'selected'
            });

            $.each(otherattr, function (ii, vv) {
                option.attr(ii, vv);
            });
            if (i == '0') {
                option.prop('selected', true);
            }
            $(_this).append(option);
        } else {
            oEl.text(name);
            //oEl.prop("selected", true);

            $.each(otherattr, function (ii, vv) {
                oEl.attr(ii, vv);
            });
            if (i == '0') {
                oEl.prop('selected', true);
            }

        }
    });

}


function setEditorConfig() {
    CKEDITOR.on('instanceReady', function (event) {
        var editor = event.editor;
        editor.on('change', function (event) {
            // Sync textarea
            this.updateElement();
        });
    });
    var ckFinderUrl = PROJECT_URL + 'backend/js/CKEditor/ckfinder/';
    CKEDITOR.config.filebrowserBrowseUrl = ckFinderUrl + 'ckfinder.html';
    CKEDITOR.config.filebrowserImageBrowseUrl = ckFinderUrl + 'ckfinder.html?type=Images';
    CKEDITOR.config.filebrowserFlashBrowseUrl = ckFinderUrl + 'ckfinder.html?type=Flash';
    CKEDITOR.config.filebrowserUploadUrl = ckFinderUrl + 'core/connector/php/connector.php?command=QuickUpload&type=Files';
    CKEDITOR.config.filebrowserImageUploadUrl = ckFinderUrl + 'core/connector/php/connector.php?command=QuickUpload&type=Images';
    CKEDITOR.config.filebrowserFlashUploadUrl = ckFinderUrl + 'core/connector/php/connector.php?command=QuickUpload&type=Flash';
}
function drawEditor() {
    $('.CKEDITOR:not(.CKEDITOR_ADDED)').each(function () {
        var _this = $(this);
        _this.addClass('CKEDITOR_ADDED');
        var id = _this.attr('id');
        id = id + Math.random().toString(36).substring(7);
        _this.attr('id', id);
        CKEDITOR.replace(id);
    });
}

function drawEditorOld() {
    $('.summernote').each(function () {
        var _this = $(this);
        var progressEl = $("<progress></progress>").insertBefore(_this).hide();
        // update progress bar

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
                [
                    'style',
                    [
                        'style'
                    ]
                ],
                [
                    'style',
                    [
                        'bold',
                        'italic',
                        'underline',
                        'clear'
                    ]
                ],
                [
                    'fontsize',
                    [
                        'fontsize'
                    ]
                ],
                [
                    'para',
                    [
                        'ul',
                        'ol',
                        'paragraph'
                    ]
                ],
                [
                    'height',
                    [
                        'height'
                    ]
                ],
                [
                    'insert',
                    [
                        'picture',
                        'link',
                        'video'
                    ]
                ],
                [
                    'view',
                    [
                        'fullscreen',
                        'codeview'
                    ]
                ],
                        // ['table', ['table']],
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
                    url: PROJECT_URL + "admin/users/fileupload",
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
}

function setNavigaction() {
    var path = window.location.origin + window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
    $('#nav-accordion a:not(.dcjq-parent)').each(function () {
        var href = $(this).prop('href');
        href = href.replace(/\/$/, "");
        href = decodeURIComponent(href);
        //var is_contain = (path.indexOf(href) > -1);
        if (path == href) {// || is_contain
            setNavigactionClass(this)
        }
    });
    $(".dcjq-parent.active").next('.sub').show();
}

function setNavigactionClass(_this) {
    if ($(_this).parents('#nav-accordion').length) {
        $('#nav-accordion .active').removeClass('active');
        $(_this).addClass('active');
        $(_this).closest('li').addClass('active');
        $(_this).closest('.sub').prev('.dcjq-parent').addClass('active');
        $(_this).closest('.sub').prev('.dcjq-parent').closest('.sub').prev('.dcjq-parent').addClass('active');
    }
}

function log(val)
{
    console.log(val);
}

function callAjaxLinkHtml(ajaxURL, _this, removeDiv) {

    var dataType = 'html';
    var postGet = 'get';
    var removeTr = false;
    var data = {};
    if (ajaxURL == undefined || ajaxURL == '') {
        return false;
    }
    if (ajaxURL == 'javascript:void(0);') {
        if (_this.attr('link') != undefined && _this.attr('link') != null && _this.attr('link') != '') {
            ajaxURL = _this.attr('link');
        }
    }
    if (ajaxURL == 'javascript:void(0);') {
        return false;
    }
    if (ajaxURL == '#AccessDenied') {
        $error = 'Access Denied!';
        errorAlert($error);
        return false;
    }
    if (_this != undefined && _this != null && _this.hasClass("removeTr")) {
        dataType = 'json';
        if (_this.hasClass("bulk_delete")) {
            var anyChecked = $("input:checkbox.toggle_child:checked", _this.parents('table.dataTable')).map(function () {
                return $(this).val();
            }).get().join(",");
            if (anyChecked.length <= 0) {
                alert("Please select at least one row checkbox!");
                return false;
            }
            ajaxURL += '/' + anyChecked;
        }
        if (_this.attr("DataMsg")) {
            var Msg = _this.attr("DataMsg");
            if (confirm(Msg) == false) {
                return false;
            }
        } else {
            if (confirm("Are you sure to delete record?") == false) {
                return false;
            }
        }


        removeTr = true;
        postGet = 'post';
        data = {'pageaction': 'delete'};
    }
    
    var onlyHas = ajaxURL.replace(PROJECT_URL_WITH_PREFIX, '');
    var fullUrl = ajaxURL.replace(PROJECT_URL_WITH_PREFIX, PROJECT_URL_WITH_PREFIX + '#');
    if (removeTr != true) {
        window.location.hash = onlyHas;
    }

    $.ajax({
        type: postGet,
        url: ajaxURL,
        cache: false,
        dataType: dataType,
        data: data,
        async: false,
        headers: {'header-layout': 'ajaxlink'},
        beforeSend: function () {
            addLoader();
        },
        error: function (er) {
            log(er);
//      log(ajaxURL);
        },
        success: function (successdata, status, xhr) {
            //$("#main-content .wrapper").empty().html(successdata);
            //log(successdata);
            removeLoader();
            if (checkResponceData(successdata, dataType, xhr)) {
                return;
            }
            if (removeTr == true) {

                var dataTableCheckBoxObj = $(_this.parents('table')).dataTable();
                dataTableCheckBoxObj.fnStandingRedraw();
                checkAlert(successdata);
            } else
            {
                if ($(removeDiv).length > 0) {
                    $(removeDiv).remove();
                }
                var divContent = $("#main-content .wrapper:not(.content-hide)");
                if (divContent.length <= 0) {
                    var divContent = $("#main-content .wrapper").eq('0');
                }
                if (divContent.length <= 0) {
                    $("#main-content").append('<section class="wrapper"></section>');
                    var divContent = $("#main-content .wrapper").eq('0');
                }

                $("html, body").removeClass('height100P');
                $(divContent).empty().html(successdata);
                divContent.removeClass('content-hide').show();
                $("#main-content .wrapper.content-hide").hide();

            }
            loadAllFuntion();
        }
    });
}

function checkAlert(successdata) {
    $success = $error = $warning = '';
    if (successdata.success != undefined) {
        $success = successdata.success;
    }
    if (successdata.error != undefined) {
        $error = successdata.error;
    }
    if (successdata.warning != undefined) {
        $warning = successdata.warning;
    }
    showAlert($success, $error, $warning);
}

function checkResponceData(successdata, dataType, xhr) {
    var ret = 0;
    if (dataType == 'html') {

        if (xhr.getResponseHeader('Content-Type') == 'application/json') {
            var successdataTemp = JSON.parse(successdata);

            if (successdataTemp.data != undefined && successdataTemp.data.url != undefined)
            {
                window.location = successdataTemp.data.url;
            }
            $success = $error = $warning = '';
            if (successdataTemp.error != undefined && successdataTemp.status != undefined && successdataTemp.status == '0')
            {
                $error = successdataTemp.error;
            }
            ret = showAlert($success, $error, $warning);
            ret = 1;
        }
    }
    return ret;
}


function showAlert($success, $error, $warning) {
    removeLoader();
    var ret = 0;
    if ($success != undefined && $success != '')
    {
//    swal({//sweetalert js and css use for swal
//      title: "Good job!",
//      text: $success,
//      type: "success",
//      timer: 5000,
//      //showConfirmButton: false
//      //confirmButtonText: 'Close',
//    });
        //swal("Good job!", $success, "success");
        //alert($success);

        successAlert($success);

        ret++;
    }
    if ($warning != undefined && $warning != '')
    {
//    swal({//sweetalert js and css use for swal
//      title: "oops!",
//      text: $warning,
//      type: "warning",
//      timer: 5000
//    });
        //swal("oops!", $warning, "warning");
        //alert($warning);
        warningAlert($warning);
        ret++;
    }
    if ($error != undefined && $error != '')
    {
//    swal({//sweetalert js and css use for swal
//      title: "oops!",
//      text: $error,
//      type: "error",
//      timer: 5000
//    });
        //swal("oops!", $error, "error");
        //alert($error);
        errorAlert($error);
        ret++;
    }

    return ret;
//$messageAlert
}

function checkReponseOfHtml(html) {
    var ret = 1;
    try {
        var successdataTemp = JSON.parse(html);
        var $error = '';
        if (successdataTemp.error != undefined) {
            $error = successdataTemp.error;
        }
        if ($error != '') {
            errorAlert($error);
            ret = 0;
        }
    } catch (err) {
        console.log(err);
    }

    return ret;
}

function successAlert(msg) {
    Messenger().post({
        message: msg,
        type: 'success',
        showCloseButton: true
    });

}

function warningAlert(msg) {
    Messenger().post({
        message: msg,
        type: 'warning',
        showCloseButton: true
    });

}

function errorAlert(msg) {
    Messenger().post({
        message: msg,
        type: 'error',
        showCloseButton: true
    });
}

function ajaxLinkClick() {

    if (LINK_MODE == 'ajax') {
        $(document).delegate('[ajaxLink]:not(.noAjaxLink)', 'click', function (e) {
            e.preventDefault();
            var _this = $(this);
            var ajaxURL = _this.attr('href');
            callAjaxLinkHtml(ajaxURL, _this);
            setNavigactionClass(_this);
            return false;
        });
    }

}

function datatableSettings() {

    $.fn.dataTableExt.oApi.fnStandingRedraw = function (oSettings) {
        //redraw to account for filtering and sorting
        // concept here is that (for client side) there is a row got inserted at the end (for an add)
        // or when a record was modified it could be in the middle of the table
        // that is probably not supposed to be there - due to filtering / sorting
        // so we need to re process filtering and sorting
        // BUT - if it is server side - then this should be handled by the server - so skip this step
        if (oSettings.oFeatures.bServerSide === false) {
            var before = oSettings._iDisplayStart;
            oSettings.oApi._fnReDraw(oSettings);
            //iDisplayStart has been reset to zero - so lets change it back
            oSettings._iDisplayStart = before;
            oSettings.oApi._fnCalculateEnd(oSettings);
        }

        //draw the 'current' page
        oSettings.oApi._fnDraw(oSettings);
        var _this = this;
        if (_this.fnGetData().length == '1') {
            //setTimeout(function() {        if(_this.fnGetData().length == '0') {
            oSettings._iDisplayStart = oSettings._iDisplayStart - oSettings._iDisplayLength;
            log(oSettings._iDisplayStart);
            if (oSettings._iDisplayStart < 0) {
                oSettings._iDisplayStart = 0;
            } else {
                //draw the 'previous' page
                oSettings.oApi._fnDraw(oSettings);
            }
            // }      }, 1000);
        }
    };
}
function chekedValuesByElement(chkRadioEl)
{
    return $(chkRadioEl + ":checked").map(function () {
        return $(this).val();
    }).get().join(",");
}
function setDatableListing(tableId, ajaxUrl, footerCallback) {
    var option = {
        "processing": true,
        "serverSide": true,
        "bSort": false,
        "ajax": {
            url: ajaxUrl,
            type: "post",
            error: function () {
            }
        },
        "fnDrawCallback": function () {
            iCheckInit();
            toggle_childChange();
            hideLinkAccessDenied();
            $('i.fa-eye').parent("a.action").hide();
        }
    };

    if ($.isFunction(footerCallback)) {
        option.footerCallback = footerCallback;
    }

    var dataTable = $("#" + tableId).DataTable(option);

    function toggle_allChange() {
        var toggle_all = $("input:checkbox.toggle_all", "#" + tableId);
        var isChecked = toggle_all.prop("checked");


        var toggle_child = $("input:checkbox.toggle_child", "#" + tableId);
        toggle_child.prop('checked', isChecked).iCheck('update');
        var toggle_child_parents = toggle_child.parents('.cbr-replaced');
        if (isChecked) {
            toggle_child_parents.addClass('cbr-checked');
        } else {
            toggle_child_parents.removeClass('cbr-checked');
        }
        toggleBulkActions();
    }
    function toggle_childChange() {
        var anyChecked = $("input:checkbox.toggle_child:checked", "#" + tableId);
        var anyCheckedAll = $("input:checkbox.toggle_child", "#" + tableId);
        toggleBulkActions();
        var isCheckedAll = (anyChecked.length == anyCheckedAll.length && anyChecked.length) ? true : false;

        var toggle_all = $("input:checkbox.toggle_all", "#" + tableId);
        toggle_all.prop('checked', isCheckedAll).iCheck('update');
        var toggle_all_parents = toggle_all.parents('.cbr-replaced');
        if (isCheckedAll) {
            toggle_all_parents.addClass('cbr-checked');
        } else {
            toggle_all_parents.removeClass('cbr-checked');
        }
    }

    function toggleBulkRemoveActive() {
        var bulk_actions = $(".bulk-actions", "#" + tableId);
        var bulk_actions_btn = $(".dropdown-toggle", bulk_actions);
        var dropdown_menu = $(".dropdown-menu", bulk_actions);
        $('.active', dropdown_menu).removeClass('active');
    }
    function toggleBulkActions() {

        var anyChecked = $("input:checkbox.toggle_child:checked", "#" + tableId);
        var isChecked = (anyChecked.length) ? true : false;

        var bulk_actions = $(".bulk-actions", "#" + tableId);
        var bulk_actions_btn = $(".dropdown-toggle", bulk_actions);
        if (isChecked) {
            bulk_actions_btn.removeClass("disabled");
        } else {
            bulk_actions_btn.addClass("disabled");
            bulk_actions.removeClass('open');
        }
        //toggleBulkRemoveActive();
    }

    $("#" + tableId).delegate('input:checkbox.toggle_all', 'change ifChanged', function (e) {
        toggle_allChange();
    });
    $("#" + tableId).delegate('input:checkbox.toggle_child', 'change ifChanged', function (e) {
        toggle_childChange();
    });
    $("#" + tableId + "_filter").css("display", "block"); // hidding global search box

    //~ $("input[type='search']").on('change keyup', function () {
    //~ var v = $(this).val();		
    //~ $("#" + tableId).DataTable().search(v).draw();
    //~ });

    $('.search-input-text', "#" + tableId).on('change keyup', function () {   // for text boxes
        var i = $(this).attr('data-column'); // getting column index
        var v = $(this).val(); // getting search input value
        dataTable.columns(i).search(v).draw();
    });

    var SearchPanelDiv = $("#" + tableId).closest('section').find('.SearchPanelDiv');
    $('.search-input-text', SearchPanelDiv).on('change keyup', function () {   // for text boxes
        var i = $(this).attr('data-column'); // getting column index
        var v = $(this).val(); // getting search input value
        dataTable.columns(i).search(v).draw();
    });

}

function iCheckInit() {
    $('input.icheck').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        increaseArea: '20%' // optional
    });
}

function selSelectedValues(selId)
{
    return $("option:selected", $(selId)).map(function () {
        return $(this).val();
    }).get().join(",");
}
function addNewSelectOptionChange() {

    $(document).delegate('select[new_modal] option[value="new"]', "click", function (e) {

        e.preventDefault();
        var _this = $(this);
        _this.prop('selected', false);
        var val = _this.val();
        var selEl = _this.parents('select');
        var elid = selEl.prop("id");
        var new_modal = selEl.attr("new_modal");

        var foundNew = (val == 'new') ? true : false;
        var isMultiple = selEl.attr('multiple');
        if (foundNew) {
            var modalEl = $("#modal_" + new_modal);
            if (modalEl.length) {
                modalEl.attr('popupel', elid);
                modalEl.attr('new_modal', new_modal);
                modalEl.modal();
            }
        }
    });
}
$(function () {
    ajaxLinkClick();
   // datatableSettings();
    addNewSelectOptionChange();
    setNavigaction();
    loadAllFuntion();
  //  drawEditor();
  //  setEditorConfig();

});

function loadAllFuntion() {

    setModalEvent();
    setCountryStateCity();
   // drawEditor();

    $.each($('select[new_modal] option[value="new"]').parents(), function () {
        var selEl = $(this);
        var val = selSelectedValues(selEl);
        val = val.split(",");
        var found = ($.inArray("new", val) >= 0) ? true : false;
        var foundNewHide = ($.inArray("newhide", val) >= 0) ? true : false;
        if (found || foundNewHide) {
            $("option[value='new']", selEl).prop('selected', false);
            $("option[value='newhide']", selEl).prop('selected', false);
            if (selEl.prop('multiple')) {
                $("option[value='']", selEl).prop('selected', false);
            } else {
                $("option[value='']", selEl).prop('selected', true);
            }
        }

    });
    $(".numeric").numeric();
    $(".decimalVal").numeric({decimalPlaces: 2});
    $(".decimalVal6").numeric({decimalPlaces: 6});
    // setLinkTitle();
    hideLinkAccessDenied();
    sortSelectOption(asc_sort);
}

function setCountryStateCity() {
    $('body').delegate("#country-id", "change", function () {
        getState();
    });

    $('body').delegate("#state-id", "change", function () {
        getCity();
    });
    if ($("#country-id").length) {
        getState();
    }
}
function getState() {
    var val = $("#country-id").val();
    if (val != undefined && val && $("#state-id").length) {
        $.ajax({
            type: "GET",
            url: PROJECT_URL_WITH_PREFIX + "users/getState",
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-id").html(data);
                if (valSelectedState != undefined && valSelectedState) {
                    $("#state-id").val(valSelectedState);
                    valSelectedState = '';
                }
                getCity();
            }
        });
    }
}
function getCity() {
    var val = $("#state-id").val();
    if (val != undefined && val && $("#city-id").length) {
        $.ajax({
            type: "GET",
            url: PROJECT_URL_WITH_PREFIX + "users/getCity",
            data: 'state_id=' + val,
            success: function (data) {
                $("#city-id").html(data);
                if (valSelectedCity != undefined && valSelectedCity) {
                    $("#city-id").val(valSelectedCity);
                    valSelectedCity = '';
                }
            }
        });
    }
}

function cloneRemove(parentTag, removeIdEl, currentEl) {
    var removeAllid = $(removeIdEl).val();
    var parentTag = $(parentTag);
    var id = parentTag.find(currentEl).val();
    
    var allRemoveId = removeAllid + ',' + id;
    allRemoveId = cleanStr(allRemoveId, ',');
    $(removeIdEl).val(allRemoveId);
}

function cleanStr(str, expolde) {
    return str.split(expolde).filter(function (i) {
        return i
    }).join(expolde);
}

//~ function setStartEndDate(parentsDiv, startDateEl, endDateEl, deadlineEl) {
    //~ $.each($(parentsDiv), function () {
        //~ var parents = $(this);
        //~ $(startDateEl, parents).removeClass('hasDatepicker');
        //~ $(startDateEl, parents).datepicker({
            //~ onSelect: function (selected) {
                //~ $(endDateEl, parents).datepicker("option", "minDate", selected);
            //~ },
            //~ onClose: function (selected) {
                //~ if ($(startDateEl, parents).val() == '') {
                    //~ $(endDateEl, parents).datepicker("option", "maxDate", null);
                    //~ $(endDateEl, parents).datepicker("option", "minDate", null);
                //~ }
            //~ }
        //~ });
        //~ $(endDateEl, parents).removeClass('hasDatepicker');
        //~ $(endDateEl, parents).datepicker({
            //~ onSelect: function (selected) {
                //~ $(startDateEl, parents).datepicker("option", "maxDate", null);
            //~ },
            //~ onClose: function (selected) {
                //~ if ($(endDateEl, parents).val() == '') {
                    //~ $(startDateEl, parents).datepicker("option", "maxDate", null);
                //~ }
            //~ }
        //~ });
        //~ $(deadlineEl, parents).removeClass('hasDatepicker');
        //~ $(deadlineEl, parents).datepicker();
    //~ });
//~}

//date picker start

$(function () {

    // var dateFormat =  'dd/mm/yy';////set from settings
    //~ $.datepicker.setDefaults({
        //~ dateFormat: dateFormat,
        //~ changeMonth: true,
        //~ changeYear: true,
    //~ });

    //$.fn.datepicker.defaults.format = "dd-mm-yyyy";
//  $.fn.datepicker.defaults.autoclose = true;
//
////date picker start
//  $('.default-date-picker').datepicker();
//date picker end

//datetime picker start

//  $.fn.datetimepicker.defaults.format = "yyyy-mm-dd hh:ii";
//  $.fn.datetimepicker.defaults.autoclose = true;
//  $.fn.datetimepicker.defaults.minuteStep = '05';
//  $(".form_datetime").datetimepicker();

//datetime picker end

//timepicker start
//  $.fn.timepicker.defaults.format = "hh:ii";
//  $.fn.timepicker.defaults.autoclose = true;
//  $.fn.timepicker.defaults.minuteStep = '1';
//  $.fn.timepicker.defaults.showSeconds = true;
//  $.fn.timepicker.defaults.showMeridian = false;
//  $('.timepicker-default').timepicker();


//timepicker end

//colorpicker start

//  $('.colorpicker-default').colorpicker({
//    format: 'hex'
//  });
//  $('.colorpicker-rgba').colorpicker();

//colorpicker end

});
// for tooltip
// $(document).ready(function(){
// $('[data-toggle="tooltip"]').tooltip();
// });




function roundToFixed(value, toFixedVal) {
    if (toFixedVal == undefined || toFixedVal == '' || !$.isNumeric(toFixedVal)) {
        toFixedVal = 4;
    }
    return parseFloat(value).toFixed(toFixedVal);
}

function roundTo(value) {
    return Math.round(roundToFixed(value));
}
function getNumber(value, default_set, toFixedVal) {
    if (default_set == undefined || default_set == '' || !$.isNumeric(default_set)) {
        default_set = 1;
    }
    var r = $.isNumeric(value);
    if (!r) {
        value = default_set;
    }
    value = parseFloat(roundToFixed(value, 6));
    return value;
}


$.fn.serializeObject = function () {

    var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push": /^$/,
                "fixed": /^\d+$/,
                "named": /^[a-zA-Z0-9_]+$/
            };


    this.build = function (base, key, value) {
        base[key] = value;
        return base;
    };

    this.push_counter = function (key) {
        if (push_counters[key] === undefined) {
            push_counters[key] = 0;
        }
        return push_counters[key]++;
    };

    $.each($(this).serializeArray(), function () {

        // skip invalid keys
        if (!patterns.validate.test(this.name)) {
            return;
        }

        var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

        while ((k = keys.pop()) !== undefined) {

            // adjust reverse_key
            reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

            // push
            if (k.match(patterns.push)) {
                merge = self.build([
                ], self.push_counter(reverse_key), merge);
            }

            // fixed
            else if (k.match(patterns.fixed)) {
                merge = self.build([
                ], k, merge);
            }

            // named
            else if (k.match(patterns.named)) {
                merge = self.build({}, k, merge);
            }
        }

        json = $.extend(true, json, merge);
    });

    return json;
};


function hideLinkAccessDenied() {

    var el = $('[href="#AccessDenied"]:not(.AccessDenied),[href_old="#AccessDenied"]:not(.AccessDenied)');
    el.addClass('AccessDenied');

    $('.AccessDenied').unbind().off().on('click', function (event) {
        $error = 'Access Denied!';
        errorAlert($error);
        return false;
    });

    var el2 = $('.AccessDenied');
    el2.each(function () {
        var _this = $(this);
        var p = _this.parents('ul.sub');
        if (p.length) {
            var li = p.find('li a').length;
            var lia = p.find('li a.AccessDenied').length;
            if (li == lia) {
                p.parents('li').addClass('hidden');
            }
        }
    });
}


function setLinkTitle() {

//  var el = $(".brankic-pen2.ajaxLink:not(.addedtitle)");
//  el.addClass('addedtitle');
//
//  el.each(function() {
//    if($(this).attr('title') == undefined || $(this).attr('title') == '') {
//      $(this).attr('title', 'Edit');
//      //  $(this).attr('data-toggle', 'tooltip');
//    }
//  });
//
//  var el = $(".brankic-cancel3.ajaxLink:not(.addedtitle)");
//  el.addClass('addedtitle');
//
//  el.each(function() {
//    $(this).attr('href_old', $(this).attr('href'));
//    $(this).attr('href', 'javascript:void(0);');
//    if($(this).attr('title') == undefined || $(this).attr('title') == '') {
//      $(this).attr('title', 'Delete');
//      //     $(this).attr('data-toggle', 'tooltip');
//    }
//  });
    hideLinkAccessDenied();

}



function asc_sort(a, b) {

    if ($(a).val() == 'other' || $(b).val() == 'other' || $(a).val() == 'all' || $(b).val() == 'all')
    {
        return 0;
    }
    if ($(a).val() == '' || $(b).val() == '')
    {
        return 0;
    }
    var at = $(a).text(), bt = $(b).text();
    //if(typeof at === 'number' && typeof bt === 'number' ){
    //at = parseFloat(at);
    //bt = parseFloat(bt);
    //}
    return at == bt ? 0 : at < bt ? -1 : 1;
}

// decending sort
function dec_sort(a, b) {

    if ($(a).val() == 'other' || $(b).val() == 'other' || $(a).val() == 'all' || $(b).val() == 'all')
    {
        return 0;
    }
    if ($(a).val() == '' || $(b).val() == '')
    {
        return 0;
    }
    var at = $(a).text(), bt = $(b).text();
    return at == bt ? 0 : at < bt ? 1 : -1;
}


function sortSelectOption(action) {
    return;
    var allEl = $('select:not(.nosort)');

    $.each(allEl, function () {
        var selector = $(this);

        var op = $("option[selected]", selector);
        if (op.length <= 0) {
            op = $('option[value=""]', selector);
        }
        if (op.length <= 0) {
            op = $('option:first', selector);
        }

        var opts_list = selector.find('option');
        opts_list.sort(action);
        selector.html('').append(opts_list);
        op.prop('selected', true);
        op.attr('selected', 'selected');


//        
//var  options = selector.find('option');
//        var arr = options.map(function (_, o) {
//            return {t: $(o).text(), v: o.value};
//        }).get();
//        arr.sort(function (o1, o2) {
//            return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0;
//        });
//        options.each(function (i, o) {
//            o.value = arr[i].v;
//            $(o).text(arr[i].t);
//        });

    });


}

function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
}

function sortUnorderedList(ul, sortDescending) {

    if (typeof ul == "string")
        ul = document.getElementById(ul);

    // Idiot-proof, remove if you want
    if (!ul) {
        //alert("The UL object is null!");
        return;
    }

    // Get the list items and setup an array for sorting
    var lis = ul.getElementsByTagName("LI");
    var vals = [];

    // Populate the array
    for (var i = 0, l = lis.length; i < l; i++)
        vals.push(lis[i].innerHTML);

    // Sort it
    vals.sort();

    // Sometimes you gotta DESC
    if (sortDescending)
        vals.reverse();

    // Change the list on the page
    for (var i = 0, l = lis.length; i < l; i++)
        lis[i].innerHTML = vals[i];
}

myhelper = function (e) {
    return $('.name', $(e)).text();
}
