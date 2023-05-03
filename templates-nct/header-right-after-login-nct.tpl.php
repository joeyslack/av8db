<div class="toggle-srch">
 <a href="#" class="cart-buttom" title="{LBL_SEARCH}"><i class="icon-srch"></i></a>
  <div class="navbar-form navbar-left custom-dropdown header-srch-bx">
    <form  role="search" action="" method="get" name="header_search_form" id="header_search_form">
        <div class="input-group-addon">
          <div class="dropdown search-menu"> <span id="selected_entity_container" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="amount-menu" data-entity="%SELECTED_ENTITY_NAME%"><i id="search_selected_entity" class="fa %SELECTED_ENTITY_CLASS%"></i><i class="fa fa-caret-down"></i></span>
            <ul class="dropdown-menu" aria-labelledby="dLabel">
              <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_HEADER_PEOPLE}" class="search-entity-selection" data-entity="users"><i class="fa fa-user"></i>{LBL_HEADER_PEOPLE}</a></li>
              <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_HEADER_JOBS}" class="search-entity-selection" data-entity="jobs"><i class="fa fa-briefcase"></i>{LBL_HEADER_JOBS}</a></li>
              <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_HEADER_COMPANY}" class="search-entity-selection" data-entity="companies"><i class="fa fa-building"></i>{LBL_HEADER_COMPANY}</a></li>
              <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_HEADER_GROUP}" class="search-entity-selection" data-entity="groups"><i class="fa fa-users"></i>{LBL_HEADER_GROUP}</a></li>
            </ul>
          </div>
        </div>
        <input type="text" id="keyword" name="keyword" placeholder="{LBL_HEADER_SEARCH_PEOPLE_JOBS_COMPANIES_AND_MORE}" autocomplete="off" value="%KEYWORD%"/>
      <button type="submit" id="header_search_submit" name="header_search_submit" class="search-btn" title="{LBL_SEARCH}"><i class="icon-srch"></i></button>
    </form>
  </div>
</div>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav navbar-right rgt-menu">

    <li class="dropdown in-login-drodown in-link-bx in-noti">
    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="show_notification"><i class="icon-bell" title="{LBL_NOTIFICATIONS}"></i></a>
    <span class="badge-bx %CLASS_NOT%" id="notifications_count">%NOTIFICATIONS_COUNT%</span>
      <div class="dropdown-menu">
        <div class="mCustomScrollbar header-notifications">
          <ul id="all_general_notification_list" class="in-notify-list">
            %GENERAL_NOTIFICATIONS%
          </ul>
        </div>
      </div>
    </li>
    <li class="dropdown in-login-drodown in-link-bx in-msg">
    <a href="javascript:void(0);"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="icon-msg" title="{LBL_MESSAGE}"></i></a>
    <span class="badge-bx  %CLASS_MSG%" id="messages_count">%MESSAGE_COUNT%</span>
      <div class="dropdown-menu">
        <div class="mCustomScrollbar header-notifications">
          <h3 class="msg-heading-title clearfix"> 
            <a href="%MESSAGES_URL%" target="_blank" title="{LBL_MESSAGES}">{LBL_MESSAGES}</a> 
            <div class="create-msg">
              <a href="%COMPOSE_MESSAGE_URL%" title="{LBL_COMPOSE_MESSAGE}" class="icon-plus"></a>
            </div>
          </h3>
          <ul id="all-message-list" class="in-msg-head">
            %MESSAGES%
          </ul>
          <a class="view-all-notifications %CLASS_HIDE_VIEW% " title="{LBL_VIEW_ALL}" target="_blank" href="%VIEW_ALL_MESSAGES_URL%/#message">{LBL_HEADER_VIEW_ALL_MESSAGES}</a> </div>
      </div>
      
      </li>
    <li class="dropdown in-login-drodown in-link-bx in-req">
    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="icon-globe" title="{LBL_CONN_REQ}"></i></a>
    <span class="badge-bx  %CLASS_CON%" id="connection_request_count">%CONNECTION_REQUESTS_COUNT%</span>
      <div class="dropdown-menu">
        <div class="mCustomScrollbar header-notifications">
          <ul id="all_connection_requests_list" class="dropdown-ul in-req-head">
            %CONNECTION_REQUESTS%
          </ul>
        </div>
      </div>
      </li>
    <li class="dropdown in-user-head hidden-xs">
    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="<?php echo ucwords($_SESSION['first_name'])." ".ucwords($_SESSION['last_name']);?>">
      <div class="user-img">
        <?php 
          $user_img_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$_SESSION['user_id']));
          $user_img_src = 'https://storage.googleapis.com/av8db/users-nct/'.$_SESSION['user_id'].'/th4_'.$user_img_name;
          $is_image = getimagesize($user_img_src);
          $first_name = getTableValue('tbl_users','first_name',array('id'=>$_SESSION['user_id']));
          $last_name = getTableValue('tbl_users','last_name',array('id'=>$_SESSION['user_id']));
          if(!empty($is_image)){
              echo '<img src="'.$user_img_src.'" alt="'.$first_name.' '.$last_name.'">';
          }else{
              echo '<span class="profile-picture-character">'.ucfirst($first_name[0]).'</span>';
          }
        ?>
      </div>
    </a>
      <ul class="dropdown-menu">
        <li><a href="{SITE_URL}account-settings" title="{LBL_ACCOUNT_SETTINGS}"><i class="fa icon-settings"></i> {LBL_ACCOUNT_SETTINGS}</a></li>
        <li><a href="{SITE_URL}payment-history" title="{LBL_TRANSACTION_HISTORY}"><i class="icon-checklist"></i> {LBL_TRANSACTION_HISTORY}</a></li>
        <li><a href="{SITE_URL}logout" title="{LBL_LOGOUT}"><i class="icon-logout"></i> {LBL_LOGOUT}</a></li>
      </ul>
    </li>
  </ul>
</div>
<!-- Image crop model start-->
<div class="modal fade in" id="Edit_Profile1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
  <div class="modal-dialog  is-width-set" role="document">
    <div class="modal-content">
      <div class="modal-header_1"> 
        <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title_1 text-center blue-text" id="myModalLabel">Crop Image</h4>
      </div>
      <div class="modal-body_1">
        <div class="edit-profile-block">
          <div class="container2"  id="crop-avatar"> 
            <!-- Cropping modal -->
            <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post" name="avtar_form" id="avtar_form">
              <input type="hidden" name="subcat_id" id="subcat_id" value="">
              <div class="modal-body_1">
                <div class="avatar-body"> 
                  <!-- Upload image and data -->
                  <div class="avatar-upload">
                    <input type="hidden" class="avatar-src" name="avatar_src">
                    <input type="hidden" class="avatar-data" name="avatar_data">
                    <input type="hidden"  name="which_types" id="which_types">
                    <label for="avatarInput">Upload</label>
                    <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                  </div>
                  <!-- Crop and preview -->
                  <div class="row">
                    <div class="col-md-12">
                      <div class="avatar-wrapper"></div>
                    </div>
                  </div>
                  <div class="row avatar-btns">
                    <div class="col-md-12">
                      <div id="hidden_image_id" style="display:none;"></div>
                      <button id="rotateleft" class="btn btn-primary" style="float:left; margin-left:5px;margin-right:5px;"><span class="fa fa-rotate-left"></span></button>
                      <button id="rotateright" class="btn btn-primary" style="float:left; margin-left:5px;margin-right:5px;"><span class="fa fa-rotate-right"></span></button>
                      &nbsp;&nbsp;
                      <button type="button" style="float:left; margin-left:5px;margin-right:5px;width:70px;" class="btn btn-primary btn-block avatar-save" onclick="return showdata();">Done</button>
                      &nbsp;&nbsp;
                      <button type="button" style="float:left" class="btn btn-default" data-dismiss="modal" id="close_popup">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <!-- /.modal --> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
