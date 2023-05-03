<form action="" method="post" name="featured_job_form" id="featured_job_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        
        <div class="form-group"> 
            <label for="plan_description" class="control-label col-md-3">%MEND_SIGN%Description : &nbsp;</label> 
            <div class="col-md-4"> 
                <textarea name="plan_description" id="plan_description" class="form-control required">%PLAN_DESCRIPTION%</textarea>
            </div>
        </div>

        <?php echo $this->duration_and_price; ?>
        
        <div class="flclear clearfix"></div>
        <input type="hidden" name="save_featured_job_form" id="save_featured_job_form" value="save_featured_job_form" />
        <input type="hidden" name="type" id="type" value="%TYPE%"><div class="flclear clearfix"></div>
        <input type="hidden" name="id" id="id" value="%ID%"><div class="padtop20"></div>
    </div>
    <div class="form-actions fluid">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" name="save_featured_job" class="btn green" id="save_featured_job">Save</button>
        </div>
    </div>
</form>
