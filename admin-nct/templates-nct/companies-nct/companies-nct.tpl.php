<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <?php
        echo $this->breadcrumb;
        ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet box blue-dark">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="fa fa-dot-circle-o"></i><?php echo $this->headTitle; ?>
                </div>
                <div class="actions portlet-toggler">
                    %VIEW_ALL_RECORDS_BTN%
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <table id="dt_companies" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function () {
        
        ajaxSourceUrl = "<?php echo SITE_ADM_MOD.  $this->module; ?>/ajax.<?php echo $this->module; ?>.php";
        queryStringUrl = "";
        
        <?php if(isset($_REQUEST['day'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?day=<?php echo $_REQUEST['day']; ?>";
                } else {
                    queryStringUrl += "&day=<?php echo $_REQUEST['day']; ?>";
                }
        <?php } ?>
        
        <?php if(isset($_REQUEST['month'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?month=<?php echo $_REQUEST['month']; ?>";
                } else {
                    queryStringUrl += "&month=<?php echo $_REQUEST['month']; ?>";
                }
        <?php } ?>
        
        <?php if(isset($_REQUEST['year'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?year=<?php echo $_REQUEST['year']; ?>";
                } else {
                    queryStringUrl += "&year=<?php echo $_REQUEST['year']; ?>";
                }
        <?php } ?>

        <?php if(isset($_REQUEST['company_id'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?company_id=<?php echo $_REQUEST['company_id']; ?>";
                } else {
                    queryStringUrl += "&company_id=<?php echo $_REQUEST['company_id']; ?>";
                }
        <?php } ?>
        
        ajaxSourceUrl += queryStringUrl;
        
        OTable = $('#dt_companies').dataTable({
            bProcessing: true,
                bServerSide: true,
                sAjaxSource: ajaxSourceUrl,
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
                    { "sName": "id", 'sTitle': "Job Id", 'bVisible': false},
                    { sName: "company_name", sTitle : "Business Name"},
                    { sName: "company_logo", sTitle : "Business Logo"},
                    { sName: "owner_email_address", sTitle : "Business Email"},
                    { sName: "country", sTitle : "Business Location"},
                    { sName: "industry_name", sTitle : "Industry"},
                    { sName: "no_of_jobs", sTitle : "No. of Jobs"},
                    { sName: "added_on", sTitle : "Created Date"}
                    <?php if (in_array('status', $this->Permission)) { ?>
                        //,{ "sName": "status", 'sTitle' : 'Status', bSortable:false, bSearchable:false}
                    <?php } if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) { ?>
                        ,{"sName": "operation", 'sTitle': 'Operation', bSortable: false, bSearchable: false}
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

        $.validator.addMethod('pagenm', function (value, element) {
            return /^[a-zA-Z0-9][a-zA-Z0-9\-\_]*$/.test(value);
        }, 'Page name is not valid. Only alphanumeric and -,_ are allowed');
    
    });
    
    $(document).on('click', '#submitAddForm', function (e) {
        e.preventDefault();
        $("#company_form").validate({
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                company_name: {
                    required: true,
                    remote: {
                        url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
                        data: {
                            id: $('#id').val()
                        }
                    }
                },
                company_logo: {
                    accept: "jpg|jpeg|png"
                },
                company_description: {
                    required: true,
                },
                company_industry_id: {
                    required: true,
                },
                // company_size_id: {
                //     required: true,
                // },
                /*services_provided: {
                    required: true,
                },*/
                website_of_company: {
                    required: true,
                    url: true
                },
                owner_email_address: {
                    required: true,
                    email: true  
                },
                foundation_year: {
                    required: true,
                },
                company_locations: {
                    required: false
                }
            },
            messages: {
                company_name: {
                    required: "&nbsp; Please enter business name.",
                    remote: "&nbsp; Entered business name already exists."
                },
                company_logo: {
                    accept: "&nbsp; Please select a valid image file."
                },
                company_description: {
                    required: "&nbsp; Please enter business description.",
                },
                company_industry_id: {
                    required: "&nbsp; Please select compnay industry.",
                },
                // company_size_id: {
                //     required: "&nbsp; Please select compnay size.",
                // },
                /*services_provided: {
                    required: "&nbsp; Please enter the services provided.",
                },*/
                website_of_company: {
                    required: "&nbsp; Please enter the website of your business.",
                    url: "&nbsp; Please enter a valid URL."
                },
                owner_email_address: {
                    required: "&nbsp; Please enter the email id of your business.",
                    email: "&nbsp; Please enter a valid email."
                },
                foundation_year: {
                    required: "&nbsp; Please enter foundation year of your business.",
                },
                company_locations: {
                    required: "&nbsp; Please enter your business location.",
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
        
        if ($("#company_form").valid()) {
            ajaxFormSubmit("#company_form");
        } else {
            return false;
        }
    });

    var _URL = window.URL || window.webkitURL;
    $(document).on('change', "#company_logo", function (e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                width = this.width;
                height = this.height;
                aspectRatio = width / height;
                if (aspectRatio == 1 && width >= 300  && height >= 300) {

                } else {
                    toastr["error"]("Please upload the business logo of 300px X 300px or of the same aspect ratio.");
                    $("#company_logo").replaceWith($("#company_logo").val('').clone(true));
                    return false;
                }
                return true;
            };
            img.src = _URL.createObjectURL(file);
        }
    });
    
    $(document).on('click','#assign_company',function(){
        var selected_user = $("#user_id option:selected").val();
        var companyid = $(this).attr('data-comId');
        var currentUserId = $(this).attr('data-currentUserId');
        if(confirm('Are you sure to reassign the business?')){
            if(selected_user != '' && companyid != ''){
                $.ajax({
                    type: "POST",
                    url: "ajax.<?php echo $this->module; ?>.php",
                    data: {
                        action      : 'assign_company',
                        user_id     : selected_user,
                        companyid   : companyid,
                        currentUserId: currentUserId
                    },
                    cache: false,
                    beforeSend: function(){
                        addOverlay();
                    },
                    success: function (dataRes) {
                        var res = JSON.parse(dataRes);
                        $('#myModal_autocomplete').modal('hide');
                        toastr[res.type](res.message); 
                    },
                    complete: function (xhr) {
                        removeOverlay();
                        return false;
                    }
                });
            }else{
                toastr["error"]("Please select user to reassign the business.");
                return false; 
            }
        }
    });
</script>
