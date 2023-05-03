<li class="admin-cell" data-user-id="%USER_ID_ENCRYPTED%">
    <input type="hidden" name="company_admin_ids[]" id="company_admin_ids_%UNIQUE_IDENTIFIER%" value="%USER_ID_ENCRYPTED%" />
    <div class="user-img">%USER_PROFILE_PICTURE%</div>
    <div class="icon-btns deleteicon"><a href="javascript:void(0);" class="remove-company-admin" data-user-id="%USER_ID_ENCRYPTED%"><i class="fa fa-close"></i></a></div>
    <h5><a href="%PROFILE_URL%" class="blue-color" title="%USER_NAME%">%USER_NAME%</a></h5>
    <?php echo $this->user_headline; ?>
</li>