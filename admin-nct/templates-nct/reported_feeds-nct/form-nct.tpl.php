<form action="" method="post" name="ferry_pilot_form" id="ferry_pilot_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        <div class="form-group">
            <label class="control-label col-md-3">%MEND_SIGN% Feed Description: &nbsp;</label> 
            <div class="col-md-4">
                <input type="text" name="feed_desc" id="feed_desc" value="%FEED_DESCRIPTION%" class="form-control" required="">     
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