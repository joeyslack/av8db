<li class="admin-cell" data-user-id="%USER_ID_ENCRYPTED%" id="member_%USER_ID%">
    <input type="hidden" name="approve_member_ids[]" id="approve_member_ids_%UNIQUE_IDENTIFIER%" value="%USER_ID_ENCRYPTED%" />
    <div class="comment-img">
    <a href="%PROFILE_URL%" class="comment-img" title="%USER_NAME%">
        %USER_PROFILE_PICTURE%
    </a>
    </div>
    <div class="comm-cell-bx">
        <h3>
        <a href="%PROFILE_URL%" class="blue-color" title="%USER_NAME%">
            %USER_NAME%
        </a>
        
    </h3>
    <?php echo $this->user_headline; ?>
    </div>
    <div class="unfollow-lnk">
    <a href="javascript:void(0);" title="{LBL_REMOVE}" class="remove" data-id="%USER_ID_ENCRYPTED%" data-user-id="%USER_ID%">
        <i class="fa fa-trash-o"></i>{LBL_REMOVE}
    </a>
    </div>
    
</li>
