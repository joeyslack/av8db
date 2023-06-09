<form action="" method="post" name="frmCont" id="frmCont" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        <div class="form-group">
            <label class="control-label col-md-3">
                Country:&nbsp;
            </label>
            <div class="col-md-4">
                <select name="CountryID" id="CountryID" class="form-control selectBox-bg required">%COUNTRY_OPTION%</select>
            </div>
        </div>
        <div class="form-group">
            <label for="stateName" class="control-label col-md-3">
                %MEND_SIGN%
                State : &nbsp;
            </label>
            <div class="col-md-4">
                <input type="text" class="form-control logintextbox-bg required" name="stateName" id="stateName" value="%STATE_NAME%"></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Status: &nbsp;</label>
            <div class="col-md-4">
                <div class="radio-list" data-error-container="#form_2_Status: _error">
                    <label class="">
                        <input class="radioBtn-bg required" id="y" name="isActive" type="radio" value="y" %STATUS_A%>Active</label>
                    <span for="isActive" class="help-block"></span>
                    <label class="">
                        <input class="radioBtn-bg required" id="n" name="isActive" type="radio" value="n" %STATUS_D%>Deactive</label>
                    <span for="isActive" class="help-block"></span>
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
            <button type="submit" name="submitAddForm" class="btn green" id="submitAddForm">Save</button>
            <button type="button" name="cn" class="btn btn-toggler" id="cn">Cancel</button>
        </div>
    </div>
</form>