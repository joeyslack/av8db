<!-- %STICKY_BUTTONS%
 --><header class="header-sec cf">
  <nav class="navbar">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"><span class="sr-only">{LBL_TOGGLE_NAV}</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
        <a class="navbar-brand" href="{SITE_URL}" title="{SITE_NM}"><img src="https://storage.googleapis.com/av8db/site-images-nct/{SITE_LOGO}" alt="{SITE_NM}" /></a></div>
        <div class="toggle-srch">
             <a href="#" class="cart-buttom" title="{LBL_SEARCH}"><i class="icon-srch"></i></a>
        <div class="navbar-form navbar-left custom-dropdown header-srch-bx">
        <form role="search" action="" method="get" name="header_search_form" id="header_search_form">
              <div class="input-group-addon">
                <div class="dropdown search-menu"> <span id="selected_entity_container" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="amount-menu" data-entity="users"> <i id="search_selected_entity" class="fa fa-user"></i> <i class="fa fa-caret-down"></i> </span>
                  <ul class="dropdown-menu" aria-labelledby="dLabel">
                    <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_HEADER_PEOPLE}" class="search-entity-selection" data-entitya="users"><i class="fa fa-user"></i>{LBL_HEADER_PEOPLE}</a> </li>
                    <li class="search-entity-selection-li"><a href="javascript:void(0);" title="{LBL_COMPANY}" class="search-entity-selection" data-entity="companies"><i class="fa fa-building"></i>{LBL_COMPANY}</a> </li>
                  </ul>
                </div>
              </div>
              <input type="text" id="keyword" name="keyword" placeholder="{LBL_SEARCH_COMPANIES}" autocomplete="off" value="">
            <button type="submit" id="header_search_submit" name="header_search_submit" title="{LBL_SEARCH}"><i class="icon-srch"></i> </button>
        </form>
      </div>
      </div>
      <!--  -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right rgt-menu">
          <li id="login_dropdown" class="dropdown">
              <a href="javascript:void(0);" id="login_link" class="move-login login-hide" title="{LBL_LOGIN_BUTTON_SIGNIN}">{LBL_LOGIN_BUTTON_SIGNIN}</a>
              <a href="javascript:void(0);" id="login_link" class="move-signup signup-hide" title="{LBL_SIGNUP}">{LBL_SIGNUP}</a>
            <!-- <div class="dropdown-menu">%LOGIN_FORM%</div> -->
          </li>
          <?php $single_page = $this->db->select("tbl_content", "*", array("isActive" => 'y', "show_in_navigation" => "y"))->result();
                    if ($single_page) { $page_url = SITE_URL . "content/" . $single_page['page_slug']; ?>
          <li><a href="<?php echo $page_url; ?>" title="<?php echo $single_page['pageTitle']; ?>"><?php echo $single_page['pageTitle']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</header>
<script>
    $("#login_form").validate({rules: {login_email_address: {required: true, checkEmail: true},login_password: {required: true}},messages: {login_email_address: {required: "{ERROR_LOGIN_ENTER_EMAIL_ADDRESS}"},login_password: {required: "{ERROR_LOGIN_ENTER_PASSWORD}"}}});
    $("#login_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {removeOverlay();return false;}
    });
</script>
