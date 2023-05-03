<form action="" method="post" name="adhoc_inmails_form" id="adhoc_inmails_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        
        <div class="form-group"> 
            <label for="plan_description" class="control-label col-md-3">%MEND_SIGN%Plan description : &nbsp;</label> 
            <div class="col-md-4"> 
                <textarea name="plan_description" id="plan_description" class="form-control required">%PLAN_DESCRIPTION%</textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="control-label col-md-3">%MEND_SIGN%Price  : &nbsp;</label>
            <div class="col-md-4"> 
                <input type="number" class="form-control logintextbox-bg required checkFloat" name="price" id="price" value="%PRICE%" min="1" />
            </div>
        </div>
        
        <div class="flclear clearfix"></div>
        <input type="hidden" name="save_adhoc_inmails_form" id="save_adhoc_inmails_form" value="save_adhoc_inmails_form" />
        <input type="hidden" name="type" id="type" value="%TYPE%"><div class="flclear clearfix"></div>
        <input type="hidden" name="id" id="id" value="%ID%"><div class="padtop20"></div>
    </div>
    <div class="form-actions fluid">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" name="save_adhoc_inmails" class="btn green" id="save_adhoc_inmails">Save</button>
        </div>
    </div>
</form>
