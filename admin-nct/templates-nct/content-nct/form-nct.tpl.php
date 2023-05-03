<form action="" method="post" name="frmCont" id="frmCont" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
<!--        <div class="form-group">
            <label for="page_name" class="control-label col-md-3"><font color="#FF0000">*</font>Page Name: &nbsp;</label>
            <div class="col-md-4">
                            </div>
        </div>
        <div class="clearfix"></div>-->
        <div class="form-group">
            <div class="col-md-4">

            </div>
        </div>
        <div class="clearfix"></div>
        %CONSTANT_VALUE1%
        <div class="form-group">
            <div class="col-md-4">

            </div>
        </div>
        <div class="clearfix"></div>
        %CONSTANT_VALUE2%
        <div class="form-group">
            <div class="col-md-4">

            </div>
        </div>
        <div class="clearfix"></div>
        %CONSTANT_VALUE3%
        <div class="padtop10 flclear"></div>
        <div class="form-group">
            <label for="linkType" class="control-label col-md-3">Link Type: &nbsp;</label>
            <div class="col-md-4">
                <input type="radio" name="linkType" id="inkType"  value="url" %LINK_URL%   />  URL &nbsp;
                <input type="radio" name="linkType" id="inkType"  value="page" %LINK_PAGE%     />  Page
            </div>
        </div>
        <div class="form-group" id="urlwrap" >
            <label for="url" class="control-label col-md-3">%MEND_SIGN% URL: &nbsp;</label>
            <div class="col-md-4">
                <input type="text"  name="url" id="url" value="%URL%" class="form-control" />
            </div>
        </div>
        <div class="form-group ckeditFld" id="descwrap" >
            <div class="col-md-9">
                <!-- <textarea class="ckeditor form-control textarea-bg required" name="pageDesc" id="pageDesc" data-error-container="#editor_error" style="display: none;">%PAGE_DESCRIPTION%</textarea>
                    <div id="editor_error"></div> -->

            </div>
        </div>
        <div class="clearfix"></div>
        %CONSTANT_VALUE4%
        <div class="form-group">
            <label class="control-label col-md-3">Status: &nbsp;</label>
            <div class="col-md-4">
                <div class="radio-list" data-error-container="#form_2_Status: _error">
                    <label class="">
                    <input class="radioBtn-bg required" id="y" name="isActive" type="radio" value="y" %STATIC_A%>
                    Active</label>
                    <span for="status" class="help-block"></span>
                    <label class="">
                    <input class="radioBtn-bg required" id="n" name="isActive" type="radio" value="n" %STATIC_D%>
                    Inactive</label>
                    <span for="status" class="help-block"></span>
                </div>
                <div id="form_2_Status: _error"></div>
            </div>
        </div>
        <div class="flclear clearfix"></div>
        <input type="hidden" name="type" id="type" value="%TYPE%">
        <div class="flclear clearfix"></div>
        <input type="hidden" name="id" id="id" value="%ID%">
        <div class="padtop20"></div>
    </div>
    <div class="form-actions fluid">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" name="submitAddForm" class="btn green" id="submitAddForm">Submit</button>
            <button type="button" name="cn" class="btn btn-toggler" id="cn">Cancel</button>
        </div>
    </div>
</form>
<script>
    var linkType = $("[name='linkType']:checked").val();
    if(linkType == "page"){
        $("#urlwrap").hide();
        $(".ckeditFld").show();
    }else{
        $("#urlwrap").show();
        $(".ckeditFld").hide();
    }

    $(document).on("change","[name='linkType']",function(){

        var linktype = $(this).val();
        if(linktype == 'page'){
            $("#urlwrap").hide();
            $(".ckeditFld").show();
        }else{
            $("#urlwrap").show();
            $(".ckeditFld").hide();
        }

    });
</script>