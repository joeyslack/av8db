<form action="" method="post" name="company_size_form" id="company_size_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        
        %COMPANY_SIZE%
        
        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Minimum no. of employees : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="number" min="1" class="form-control required" name="minimum_no_of_employee" id="minimum_no_of_employee" value="%MINIMUM_NO_OF_EMPLOYEE%" />
            </div>
        </div>
        
        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Maximum no. of employees : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="number" min="1" class="form-control required" name="maximum_no_of_employee" id="maximum_no_of_employee" value="%MAXIMUM_NO_OF_EMPLOYEE%" />
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
