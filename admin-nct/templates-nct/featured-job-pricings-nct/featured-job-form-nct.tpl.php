<form action="" method="post" name="featured_job_form" id="featured_job_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">

        %PLAN_DESCRIPTION%
        
        

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
