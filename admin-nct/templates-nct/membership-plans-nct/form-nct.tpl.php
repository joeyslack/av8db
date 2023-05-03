<form action="" method="post" name="frmCont" id="frmCont" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        %TITLE%
        %DESRIPTION%

        <?php echo $this->regular_plan_details; ?>

        <div class="form-group"> 
            <label for="price" class="control-label col-md-3">%MEND_SIGN%Price  : &nbsp;</label>
            <div class="col-md-4"> 
                <input type="number" class="form-control logintextbox-bg required checkFloat" name="price" id="price" value="%PRICE%" min="1" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">Status: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg required" id="a" name="status" type="radio" value="a" %STATUS_A% /> Active
                    </label>
                    <span for="status" class="help-block"></span> 
                    
                    <label class=""> 
                        <input class="radioBtn-bg required" id="d" name="status" type="radio" value="d" %STATUS_D% /> Deactive
                    </label>
                    <span for="status" class="help-block"></span> 
                </div>
                <div id="form_2_Status: _error"></div> 
            </div>
        </div>
        <div class="flclear clearfix"></div>
        <input type="hidden" name="save_regular_plan_form" id="save_regular_plan_form" value="save_regular_plan_form" />
        <input type="hidden" name="plan_name_r" id="plan_name_r" value="%PLAN_NAME_R%" />
        <input type="hidden" name="plan_description_r" id="plan_description_r" value="%PLAN_DESCRIPTION_R%" />
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
