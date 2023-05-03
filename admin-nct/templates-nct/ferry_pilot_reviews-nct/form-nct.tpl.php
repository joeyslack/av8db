<script src="<?php echo SITE_PLUGIN;?>raty/jquery.raty.js" type="text/javascript"></script>
<form action="" method="post" name="ferry_pilot_form" id="ferry_pilot_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        <div class="form-group">
            <label class="control-label col-md-3">Select rate: &nbsp;</label> 
            <div class="col-md-4">
                <span data-score="%RATING%" data-name="org-rating" id="org_rating"></span>
            <input type="hidden" name="rating" id="rating" value="%RATING%">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">%MEND_SIGN% Review Description: &nbsp;</label> 
            <div class="col-md-4">
                <input type="text" name="review_desc" id="review_desc" value="%REVIEW_DESCRIPTION%" class="form-control">     
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
<script type="text/javascript">
    var rat = $('#rating').val();
     $("#org_rating").raty({
        score: rat
    });
</script>