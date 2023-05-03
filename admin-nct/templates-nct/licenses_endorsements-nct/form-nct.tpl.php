<form action="" method="post" name="license_endorsement_form" id="license_endorsement_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        %LICENSES_ENDORSEMENT%

         
        <div class="form-group %ISDEFAULT%"> 
            <label class="control-label col-md-3">Status: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg required" id="a" name="status" type="radio" value="y" %STATUS_A%> Active
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg required" id="d" name="status" type="radio" value="n" %STATUS_D%> Deactive
                    </label>
                    <span for="status" class="help-block"></span>
                </div>
                <div id="form_2_Status: _error"></div> 
            </div>
        </div>

        <div class="flclear clearfix"></div>
        <input type="hidden" name="type" id="type" value="%TYPE%"><div class="flclear clearfix"></div>
        <input type="hidden" name="id" id="id" value="%ID%"><div class="padtop20"></div>
    </div>

    <div class="form-actions fluid">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" name="submitAddForm" class="btn green" id="submitAddForm">Save</button>
            <button type="button" name="cn" class="btn btn-toggler" id="cn">Cancel</button>
        </div>
    </div>
</form>
