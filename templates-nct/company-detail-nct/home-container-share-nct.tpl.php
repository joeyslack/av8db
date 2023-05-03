<div class="white-box no-pad %SHARE_UPDATE_PANEL%" >
    <h3 class="gray-title">{LBL_COM_DET_ADMIN_CENTER}</h3>
    <form class="admin-center share-form" name="share_an_update_form" id="share_an_update_form" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
        <h4 class="black-color">{LBL_COM_DET_DRIVE_ENGAGEMENT}</h4>
        <p class="gray-text">{LBL_COM_DET_DAILY_COMPANY_UPDATE_TEXT}</p>
        <div class="form-group">
            <textarea class="form-control border-field txt-area-lg" name="description" id="description" placeholder="{LBL_SHARE_AN_UPDATE}*" ></textarea>
         
        </div>
        <div id="image_preview_container_main" class="form-group white-box">
            <div id="image_preview_container" class="col-sm-2 col-md-2">
                <img id="feed_image_img" src="" />
            </div>
            <div class="col-sm-10 col-md-10">
                <span id="feed_image_name">test image</span>
                <span id="feed_image_size" class="clearfix">test image</span>
            </div>
            <a href="javascript:void(0);" title="{LBL_REMOVE_IMAGE}" class="remove-feed-image">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <div class="clearfix"></div>
        <input type="hidden" name="company_id" id="company_id" value="%ENC_COMPANY_ID%">
        <div class="row">
        <div class="col-md-4 upload-img-company">
        <span id="share_update_file_upload" class="upload-btn btn-file ">
                <i class="fa fa-upload" aria-hidden="true"></i> {UPLOAD_AN_IMAGE}
                <input type="file" id="feed_image" name="feed_image" />
        </span>
        </div>
        <div class="col-md-8">
        <div class="form-group inline-form share-with-option">
            <select id="shared_with" name="shared_with" class="selectpicker show-tick form-control">
                <option value="p">{LBL_SHARE_WITH_PUBLIC}</option>
                <option value="c">{LBL_SHARE_WITH_CONNECTIONS}</option>
            </select>
            <button type="submit" class="btn small-btn" id="share_update_from_company" name="share_update_from_company">{LBL_COM_DET_SHARE}</button>
        </div>
        </div>
        </div>
    </form>
    <div class="clearfix"></div>
</div>