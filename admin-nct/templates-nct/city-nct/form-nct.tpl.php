<form action="" method="post" name="frmCont" id="frmCont" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        <div class="form-group">
            <label class="control-label col-md-3"> %MEND_SIGN%
                Country:&nbsp;
            </label>
            <div class="col-md-4">
                <select name="country" id="country" class="form-control selectBox-bg required" onchange="javascript:changeCountry(this.value)">
                    <option value="">Please Select</option>
                    %COUNTRY_OPTION%
                </select>
            </div>
        </div>

        <div id="statebox1">
            <div class="form-group">
                <label class="control-label col-md-3"> %MEND_SIGN%
                    State:&nbsp;
                </label>
                <div class="col-md-4">
                    <div id="statebox">
                        <select name="state" id="state" class="form-control selectBox-bg required">
                            <option value="">Please Select</option>
                            %STATE_OPTION%
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="cityName" class="control-label col-md-3">
                %MEND_SIGN%
                City : &nbsp;
            </label>
            <div class="col-md-4">
                <input type="text" class="form-control logintextbox-bg required" name="cityName" id="cityName" value="%CITY_NAME%" />
            </div>
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