<div class="post-cell gen-wht-bx  feed_post_delete" data-feed-id="%FEED_ID_ENCRYPTED%" id="%FEED_ID_ENCRYPTED%" >
    <div class="in-heading">
        %ACTIVITY%
    </div>
    <div class="in-feed-pro-table">
        <div class="in-img-70">
            %USER_PROFILE_PICTURE%
        </div>
        <div class="feed-pro-dtl-info">
            <h2>
                <a href="%USER_PROFILE_URL%" title="%USER_NAME_FULL%">%USER_NAME_FULL%</a>
            </h2>
            <span class="feed-headline">%HEADLINE%</span>
            <small>%TIME_AGO%</small>
            <div class="dropdown pull-right view-post-collapse %DROPDOWN_HIDE%">
                <a href="javascript:void(0);" class="dropdown-toggle view-post-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
                <ul class="dropdown-menu ">
                    <li><a href="javascript:void(0);" class="%VIEW_HIDE_PUB% publish_post_save" title="{LBL_PUBLISH_POST}" data-id="%FEED_ID_ENCRYPTED%" >{LBL_PUBLISH_POST}</a></li>
                    <li><a href="%FEED_URL%" class="%VIEW_HIDE%" title="{LBL_VIEW_POST}" target="_blank">{LBL_VIEW_POST}</a></li>
                    <li><a href="%EDIT_FEED_URL%" title="{LBL_EDIT_POST_BTN}" class="%HIDE_CLASS%">{LBL_EDIT_POST_BTN}</a></li>
                    <li><a href="javascript:void(0);" title="{LBL_DELETE_POST_BTN}" data-id="%FEED_ID_ENCRYPTED%" class="%HIDE_CLASS% delete_feed" >{LBL_DELETE_POST_BTN}</a></li>
                    <?php echo $this->post_actions; ?>
                </ul>
            </div>
        </div>  
    </div>
    <div class="topinfo-post-dashboard cf">
        <a href="%FEED_URL_NEW%" target="_blank"><h3>%TITLE%</h3></a>

        <p class="comment-main-dasbhoard post_description  %VIEW_FULL_POST%">%DESCRIPTION% <a href="%FEED_URL%" class="%VIEW_HIDE% %HIDE_FEED_VIEW%" title="{LBL_COM_DET_VIEW_MORE}" target="_blank">{LBL_COM_DET_VIEW_MORE}</a></p>
        %POST_IMAGE%
        <div class="clearfix"></div>
        <div class="embed-responsive embed-responsive-16by9 %VIDEO_CLASS%  %VIDEO_SPACE_CLASS%">
        %POST_VIDEO%
        </div>
    </div>
    <div class="author-fed-bx">
     %ORIGINAL_AUTHOR%
     </div>
    <div class="in-like-share-comm cf %COMMENT_HIDE%">
        %LIKE_COMMENT_SHARE_LINKS%
    </div>
    <div class="comment-form-container %COMMENT_HIDE%">
        <div class="comments-container-mcustomscroll">%COMMENTS%</div>
        %COMMENT_FORM%
    </div>
</div>