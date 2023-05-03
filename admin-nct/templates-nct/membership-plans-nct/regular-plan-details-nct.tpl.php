<div class="form-group"> 
    <label for="plan_duration" class="control-label col-md-3">%MEND_SIGN%Plan duration  : &nbsp;</label>
    <div class="col-md-4"> 
        <input type="number" class="form-control logintextbox-bg required checkNumber" name="plan_duration" id="plan_duration" value="%PLAN_DURATION%" min="1"/>
    </div>
</div>

<div class="form-group"> 
    <label class="control-label col-md-3">Plan duration unit: &nbsp;</label> 
    <div class="col-md-4"> 
        <div class="radio-list" data-error-container="#form_2_Status: _error"> 
            <label class=""> 
                <input class="radioBtn-bg required" id="plan_duration_unit_m" name="plan_duration_unit" type="radio" value="m" %PLAN_DURATION_M_CHECKED% /> Month
            </label>
            <span for="status" class="help-block"></span> 

            <label class=""> 
                <input class="radioBtn-bg required" id="plan_duration_unit_y" name="plan_duration_unit" type="radio" value="y" %PLAN_DURATION_Y_CHECKED% /> Year
            </label>
            <span for="status" class="help-block"></span> 
        </div>
        <div id="form_2_Status: _error"></div> 
    </div>
</div>
<div class="form-group %HIDE_NO_OF_INMAILS%"> 
    <label for="no_of_clinics" class="control-label col-md-3">%MEND_SIGN%No. of inmails  : &nbsp;</label>
    <div class="col-md-4"> 
        <input type="number" class="form-control logintextbox-bg required checkNumber" name="no_of_inmails" id="no_of_inmails" value="%NO_OF_INMAILS%" min="1"/>
    </div>
</div>
