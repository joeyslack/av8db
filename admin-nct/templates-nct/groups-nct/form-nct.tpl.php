<form action="" method="post" name="group_form" id="group_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Group Name : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control required" name="group_name" id="group_name" value="%GROUP_NAME%" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Group Logo : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="file" class="form-control" accept="image/*" name="group_logo" id="group_logo" />
            </div>
        </div>
        <div class="form-group">
            <label for="oldimage" class="control-label col-md-3">Old Image:&nbsp;</label>
            <div class="col-md-4">
                <img src="%IMAGE%" width="100px" height="44px" title="%GROUP_NAME%" alt="%GROUP_NAME%" />
            </div>
        </div>
        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Description : &nbsp;</label> 
            <div class="col-md-4"> 
                <textarea name="group_description" id="group_description" class="form-control required">%GROUP_DESCRIPTION%</textarea>
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Group Type : &nbsp;</label> 
            <div class="col-md-4"> 
                %GROUP_TYPE_DD%
            </div>
        </div>

        <!-- <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Group Industry : &nbsp;</label> 
            <div class="col-md-4"> 
                %GROUP_INDUSTRY_DD%
            </div>
        </div> -->

        <div class="form-group"> 
            <label class="control-label col-md-3">Privacy: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg privacy-radioBtn required" id="privacy_pu" name="privacy" type="radio" value="pu" %PRIVACY_PU% /> Public
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg privacy-radioBtn required" id="privacy_pr" name="privacy" type="radio" value="pr" %PRIVACY_PR% /> Private
                    </label>
                    <span for="status" class="help-block"></span>
                </div>
                <div id="form_2_Status: _error"></div> 
            </div>
        </div>

        <div id="accessibility_container" class="form-group %ACCESSIBILITY_HIDDEN_CLASS%"> 
            <label class="control-label col-md-3">Accessibility: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg required" id="accessibility_a" name="accessibility" type="radio" value="a" %ACCESSIBILITY_A% /> Auto join
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg required" id="accessibility_rj" name="accessibility" type="radio" value="rj" %ACCESSIBILITY_RJ% /> Request to join
                    </label>
                    <span for="status" class="help-block"></span>
                </div>
                <div id="form_2_Status: _error"></div> 
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">Status: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg required" id="a" name="status" type="radio" value="a" %STATUS_A%> Active
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg required" id="d" name="status" type="radio" value="d" %STATUS_D%> Deactive
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
            <button type="submit" name="submitAddForm" class="btn green" id="submitAddForm">Submit</button>
            <button type="button" name="cn" class="btn btn-toggler" id="cn">Cancel</button>
        </div>
    </div>
</form>
