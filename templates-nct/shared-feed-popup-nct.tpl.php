<div class="modal fade" id="share_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{LBL_GENERAL_SHARE}</h4>
            </div>
            <div class="modal-body clearfix">
                <form class="share-form fade fadeIn" name="share_an_update_form_popup" id="share_an_update_form_popup" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="shared_feed_id" id="shared_feed_id" value="" />
                    <div class="form-group">
                        <textarea class="form-control border-field" name="description_popup" id="description_popup" placeholder="{LBL_WHATS_ON_YOUR_MIND}"></textarea>
                    </div>
                    <div id="image_preview_container_main_popup" class="form-group white-box">
                        <div id="image_preview_container_popup" class="col-sm-2 col-md-2">
                            <img id="feed_image_img_popup" src="http://dev.ncryptedprojects.com/connectin/upload-nct/feed-images-nct/13014114851509617354.jpg" />
                        </div>
                        <div class="col-sm-10 col-md-10"><span id="feed_image_name_popup"></span><span id="feed_image_size_popup" class="clearfix"></span>
                        </div><a href="javascript:void(0);" title="Remove image" class="remove-feed-image-popup"><i class="fa fa-times"></i></a>
                    </div>
                    <img id="feed_image_img_popup" class="%hidePreview%" src="%IMAGE_PREVIEW%" />
                    <div class="clearfix"></div>
                    <div class="form-group inline-form">
                        <select id="shared_with_popup" name="shared_with_popup" class="selectpicker show-tick form-control border-field">
                            <option value="p">{LBL_SHARE_WITH_PUBLIC}</option>
                            <option value="c">{LBL_SHARE_WITH_CONNECTIONS}</option>
                        </select>
                        <button type="submit" class="btn small-btn" id="share_an_update_popup" name="share_an_update_popup">{LBL_GENERAL_SHARE}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>