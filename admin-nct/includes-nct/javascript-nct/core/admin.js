function displayMessagePart(isAjax) {
    if (isAjax == true) {
        urlPath = siteName + 'admin-nct/includes-nct/displayMessage-nct.php';
        $.ajax({
            type: "GET",
            url: urlPath,
            success: function (response) {
                if (response != "") {
                    $('#msgPart').html(response).show(1);
                }
            }
        });
    }
    $('#closeMsgPart').click(function () {$('#msgPart').fadeOut(1000, "linear");})
    setTimeout(function () {$('#msgPart').fadeOut(2500, "linear");}, 9000);
}
function addOverlay() {$('<div id="overlayDocument"><img src="'+SITE_ADM_IMG+'ajax-modal-loading.gif" /></div>').appendTo(document.body)}
function removeOverlay() {$('#overlayDocument').remove();}
function loadCKE(id) {var instance = CKEDITOR.instances[id];if (instance) {CKEDITOR.remove(instance);}CKEDITOR.replace(id);}
/* email validation function */
function is_validate(email) {var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;if (reg.test(email) == false) {return 'false';}}
$(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    if (confirm("Are you sure to delete record?")) {
        var $this = $(this);
        var editLink = $this.attr('href');
        $.ajax({
            url: editLink,
            type: "POST",
            dataType: "json",
            success: function (response) {
                if ('' != response.type && '' != response.message) {
                    toastr[response.type](response.message);
                    if ('success' == response.type) {
                        OTable.fnDraw(false);
                    }
                } else {
                    toastr['error']('There seems to be an issue. Please try again after some time.');
                }
            }
        });
    }
    return false;
});
$(document).on('click', '.send', function (e) {
    e.preventDefault();
    if (confirm("Are you sure to send password to this dealer?")) {
        var $this = $(this);
        var editLink = $this.attr('href');
        $.get(editLink, function (r) {
            OTable.fnDraw();
            toastr['success']('Password sent successfully.');
        });
    }
});
$(document).on('click', '.btn-viewbtn', function (e) {
    e.preventDefault();
    var $this = $(this);
    var viewLink = $this.attr('href');
    var PageTitle = $this.attr('data-page_title');
    PageTitle = (PageTitle != null) ? PageTitle : 'View details';
    $(".modal-title").html(PageTitle);
    $(".modal-body").html('<div class="popup-loader"><img src="'+SITE_ADM_IMG+'ajax-loading.gif" align="middle" /></div>');
    $("#myModal_autocomplete").modal();
    $.get(viewLink, function (r) {
        $(".modal-body").html(r);
    });
});
function ajaxFormSubmit(form_element, toggle_portlet_toggler = true) {
    $(form_element).ajaxForm({
        beforeSend: function () {addOverlay();},
        uploadProgress: function (event, position, total, percentComplete) {},
        success: function (html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"](obj.success);
                if(toggle_portlet_toggler) {
                    $('.portlet-toggler').toggle();
                    OTable.fnDraw();
                }
                return false;
            } else {
                toastr["error"](obj.error);
                return false;
            }
            return false;
        },
        complete: function (xhr) {
            removeOverlay();
            return false;
        }
    }).submit();
}
function ajaxListingFunction(divID, module, action, id, value) {
    var myConfirm;
    var urlPath;
    if (action == 'delete') {
        myConfirm = confirm('Are you sure to delete?');
    }
    else
        myConfirm = true;
    if (myConfirm == true) {
        $('#' + divID + '').html('<div style="margin:80px; text-align:center;"><div style="padding:18px;"><img src="' + siteName + 'themes/images/loadingWait.gif" alt="" border="0" /><\/div><\/div>');
        urlPath = siteName + 'admin-nct/modules-nct/' + module + '/ajax.' + module + '.php?action=' + action + '&id=' + id + '&value=' + value;
        $.ajax({
            type: "GET",
            url: urlPath,
            success: function (response) {
                if (response != "") {
                    displayMessagePart(true);
                    $('#' + divID + '').html(response);
                    /*var DTable = $('#example').dataTable();					
                     DTable.fnDraw();*/
                }
            }
        });
    }
}

function noResultsFound(sel) {
    var row = $(sel).datagrid('getRows', true);
    if (row.length == 0) {
        //alert(first_column);
        totalCols = $(sel).datagrid('getColumnFields');
        cnt = 0;
        first_column = '';
        for (i = 0; i < totalCols.length; i++) {
            a = $(sel).datagrid('getColumnOption', totalCols[i]).hidden;
            if (!a) {
                cnt += 1;
                if (cnt == 1)
                    first_column = totalCols[i]
            }
        }
        var test2 = {};
        test2[first_column] = 'No result found';
        $(sel).datagrid('insertRow', {row: test2});
        $(sel).datagrid('mergeCells', {
            index: 0,
            field: first_column,
            colspan: cnt
        });
        $('#datagrid-row-r1-2-0 td div').css({'text-align': 'center'});
    }
}
function setTitle(aoData, a) {
    aoTitles = [];
    oSettings = a.fnSettings(); 
    i = 0;
    for (ao in aoData) {
        name = aoData[ao].name;
        value = aoData[ao].value;
        if (name.substr(0, "iSortCol_".length) == "iSortCol_") {
            iCol = parseInt(name.replace("iSortCol_", ""));
            sName = "";
            if (oSettings.aoColumns[value])
                sName = oSettings.aoColumns[value].sName;
            aoTitles.push({name: "iSortTitle_" + iCol, value: sName});
            i++;
        }
    }
    for (ao in aoTitles)
        aoData.push(aoTitles[ao]);
}
$(document).on('switch-change', '.make-switch', function (event, state) {
    $(this).prop('checked', state.value);
    var val = state.value ? 'a' : 'd';
    var action = $(this).data('action');
    var switch_action = $(this).data('switch_action');
    switch_action = (switch_action != null) ? switch_action : 'updateStatus';
    $.getJSON(action, {action: switch_action, value: val}, function (r) {
        toastr[r['type']](r[0]);
    });
});
$(document).on('click', '.btn-toggler', function () {
    $('.portlet-toggler').toggle();
});
$(document).on('click', '.btnEdit', function (e) {
    e.preventDefault();
    var $this = $(this);
    var editLink = $this.attr('href');
    addOverlay();
    $.get(editLink, function (r) {
        $(".pageform").html(r);
        $(".numeric").numeric();
        removeOverlay();
        if ($('#draw_month').length) {
            $('#draw_month').datepicker({minViewMode: 1, format: 'mm/yyyy'});
        }
        $('.portlet-toggler').toggle();
        $(".date-picker").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd"
        });
    });
});
$(document).on('click', '.btnAcceptReject', function (e) {
    e.preventDefault();
    var $this = $(this);
    var AcceptRejectLink = $this.attr('href');
    addOverlay();
     $.ajax({
        dataType: 'json',
        type: "POST",
        url: AcceptRejectLink,
        success: function (html, statusText, xhr, $form) {
            obj = html;
            if (obj.status) {
                toastr["success"](obj.message);
                 OTable.fnDraw();
                return false;
            } else {
                toastr["error"](obj.message);
                return false;
            }
            return false;
        },
        complete: function (xhr) {
            removeOverlay();
            return false;
        }
    }); 
});
$(document).on('click', '.btnEditSpecs', function (e) {
    e.preventDefault();
    var $this = $(this);
    var editLink = $this.attr('href');
    addOverlay();
    $.get(editLink, function (r) {
        $(".pageform").html(r);
        $(".numeric").numeric();
        removeOverlay();
        if ($('#draw_month').length) {
            $('#draw_month').datepicker({minViewMode: 1, format: 'mm/yyyy'});
        }
        $('.portlet-toggler').toggle();
        $('.back-btn-portlet-toggler').toggle();
        $(".date-picker").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd"
        });
    });
});
$(document).on('click', '.back_to_subcategories', function () {
    $('.portlet-toggler').toggle();
    $('.back-btn-portlet-toggler').toggle();
});
$(document).on('click', '.btn-add', function (e) {
    e.preventDefault();
    var $this = $(this);
    var editLink = $this.attr('href');
    addOverlay();
    $.get(editLink, function (r) {
        $(".pageform").html(r);
        $(".numeric").numeric();
        if ($('#draw_month').length) {
            $('#draw_month').datepicker({minViewMode: 1, format: 'mm/yyyy'});
        }
        removeOverlay();
        $('.portlet-toggler').toggle();
        
    });
});
$(document).on('click', '.btnAppr', function (e) {
    var ans = confirm("Are you sure, Do you want to approve winner?");
    if (ans == false)
        return false;
    e.preventDefault();
    var $this = $(this);
    var editLink = $this.attr('href');
    addOverlay();
    $.get(editLink, function (r) {
        $(".pageform").html(r);
        var p = JSON.parse(r);
        toastr[p['type']](p[0]);
        removeOverlay();
        location.reload();
    });
});
$(document).on('click', '.btn-review-of-the-day', function (e) {
    e.preventDefault();
    var $this = $(this);
    var reviewLink = $this.attr('href');
    addOverlay();
    $.ajax({
        dataType: 'json',
        type: "POST",
        url: reviewLink,
        success: function (html, statusText, xhr, $form) {
            obj = html;
            if (obj.status) {
                toastr["success"](obj.message);
                 OTable.fnDraw();
                return false;
            } else {
                toastr["error"](obj.message);
                return false;
            }
            return false;
        },
        complete: function (xhr) {
            removeOverlay();
            return false;
        }
    }); 
    
});
$(document).on('click', '.btn-viewImage', function (e) {
    e.preventDefault();
    var $this = $(this);
    var sliderImageLink = $this.attr('href');
    addOverlay();
    $.get(sliderImageLink, function (r) {
        $(".pageform").html(r);
        $(".numeric").numeric();
        removeOverlay();
        $('.portlet-toggler').toggle();
    });
});
$(document).on('click', '.btn-removeImage', function (e) {
    e.preventDefault();
    if(confirm("Do you want to remove slider image from home page?")) {
        var $this = $(this);
        var sliderImageLink = $this.attr('href');
        addOverlay();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: sliderImageLink,
            success: function (html, statusText, xhr, $form) {
                obj = html;
                if (obj.status) {
                    toastr["success"](obj.message);
                     OTable.fnDraw();
                    return false;
                } else {
                    toastr["error"](obj.message);
                    return false;
                }
                return false;
            },
            complete: function (xhr) {
                removeOverlay();
                return false;
            }
        }); 
    }else{
        return false;
    } 
});
$(document).ready(function () {
    $(".date-picker").datepicker({
        autoclose: true,
        format: "yyyy-mm-dd"
    });
    $(document).on('keydown', '.checkFloat', function (e) { 
        if ($.inArray(e.keyCode, [46,8,9,27,13,110]) !== -1 || (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
        (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    $(document).on('keydown', '.checkNumber', function (e) { 
        if ($.inArray(e.keyCode,[46,8,9,27,13]) !== -1 || (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
    (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});
