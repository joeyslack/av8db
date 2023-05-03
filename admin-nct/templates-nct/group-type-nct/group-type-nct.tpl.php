<div class="row"><div class="col-md-12"><?php echo $this->breadcrumb; ?></div></div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-dark">
            <div class="portlet-title ">
                <div class="caption"><i class="fa fa-dot-circle-o"></i><?php echo $this->headTitle; ?></div>
                <div class="actions portlet-toggler">
                    <?php if (in_array('add', $this->Permission)) { ?>
                        <a href="ajax.<?php echo $this->module; ?>.php?action=add" class="btn blue btn-add"><i class="fa fa-plus"></i> Add</a>
                    <?php } ?>
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <table id="dt_group_type" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function () {
        OTable = $('#dt_group_type').dataTable({
            bProcessing: true,
                bServerSide: true,
                sAjaxSource: "ajax.<?php echo $this->module; ?>.php",
                fnServerData: function (sSource, aoData, fnCallback) {
                    $.ajax({
                        dataType: 'json',
                        type: "POST",
                        url: sSource,
                        data: aoData,
                        success: fnCallback
                    });
                },
                "aaSorting" : [],
                aoColumns: [
                { "sName": "id", 'sTitle': "Group Type ID", 'bVisible': false},
                { sName: "group_type_<?= DEFAULT_LANGUAGE_ID ?>", sTitle : "Group Type(<?= DEFAULT_LANGUAGE_TITLE ?>)"}
                    <?php if (in_array('status', $this->Permission)) { ?>
                    , { "sName": "status", 'sTitle' : 'Status', bSortable:false, bSearchable:false}
                <?php } ?>
                <?php if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) { ?>
                    , {"sName": "operation", 'sTitle': 'Operation', bSortable: false, bSearchable: false}
                <?php } ?>
                ],
                fnServerParams: function(aoData){setTitle(aoData, this)},
                fnDrawCallback: function(oSettings) {
                $('.make-switch').bootstrapSwitch();
                $('.make-switch').bootstrapSwitch('setOnClass', 'success');
                $('.make-switch').bootstrapSwitch('setOffClass', 'danger');

            }
    });
    $('.dataTables_filter').css({float: 'right'});
    $('.dataTables_filter input').addClass("form-control input-inline");
    $('.dataTables_length select').addClass("form-control");

    

    
    });

    $(document).on('click', '#submitAddForm', function (e) {
        e.preventDefault();
        $("#group_type_form").validate({
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                status: {
                    required: true,
                },
               
            },
            messages: {
                status: {
                    required: "&nbsp; Please enter group type description.",
                },
                '.group_type': {
                    required: "&nbsp; Please enter group name.",
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element);
                }
            }
        });
        
        ajaxFormSubmit("#group_type_form");
        /*if ($("#group_type_form").valid()) {
            alert('test')
        } else {
            alert('in')
            return false;
        }*/
    });


    
</script>