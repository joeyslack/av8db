<!-- <link href="{SITE_THEME_CSS}strength.css" rel="stylesheet" type="text/css" />-->
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://apis.google.com/js/api:client.js"></script>
<script type="text/javascript" src="{SITE_JS}social-nct.js"></script>
<div class="anime-bg-img">
	<div class="girisback lft-img-bg" style="background-image:url({SITE_THEME_IMG}login-bg.jpg)">
		<h2 class="fade fadeIn">{LBL_LOGIN_TEXT}</h2>
	</div>
	<div class="kayitback rgt-img-bg" style="background-image:url({SITE_THEME_IMG}login-bg.jpg)">
		<h2 class="fade fadeIn">{LBL_HOME_WELCOME_TEXT}</h2>
	</div>
</div>
	  <div class="anime-slide" id="textbox">
  		<div class="slide-outer-bx c-scroll">
    	   <div class="lft-login">
                <div class="in-connect-form">
        			<h3 class="text_chg_fg">{LBL_LOGIN_BUTTON_SIGNIN}</h3>
        			<div class="login-inner">
        				%LOGIN_FORM%
        			</div>
                </div>
    	   </div>
  		<!-- %SIGN_UP_FORM% -->
        <div class="rgt-signup %SIGNUP_HIDDEN%">
            <div class="in-connect-form">
                <h3>{LBL_SIGNUP}</h3>
                <form name="signup_form" id="signup_form" action="" method="post">
                    <div class="list-form cf">
                        <div class="col-sm-6 col-md-6">
                            <div class="md-input form-group">
                                <label>{LBL_SIGNUP_FIRST_NAME}<sup>*</sup></label>
                                    <input type="text" name="first_name" id="first_name" />
                                </div>
                            </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="md-input form-group">
                                <label>{LBL_SIGNUP_LAST_NAME}<sup>*</sup></label>
                                <input type="text" name="last_name" id="last_name"/>
                            </div>
                        </div>
                    </div>
                    <div class="list-form cf">
                        <div class="col-sm-6 col-md-6">
                            <div class="md-input form-group">
                                <label>{LBL_EMAIL}<sup>*</sup></label>
                                    <input type="email" name="signup_email_address" id="signup_email_address" />
                                </div>
                            </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="md-input form-group">
                                <label>{LBL_SIGNUP_PASSWORD} <sup>*</sup></label>
                                    <input type="password" name="signup_password" id="signup_password" class="form-control" />
                                    <span toggle="#signup_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    <div class="jfk-bubble hide" id="password_tooltip" role="alertdialog" aria-describedby="bubble-10">
                                        <div class="jfk-bubble-content-id" id="bubble-10">
                                          <div class="password-strength">
                                            <p><strong>{LBL_SIGNUP_PASSWORD_STRENGTH}: </strong><span id="passwdRating"></span></p>
                                            <div class="meter" id="passwdBar"><span id="strength-bar"></span></div>
                                          </div>
                                          <div>{LBL_SIGNUP_AT_LEAST_SIX_CHARACTERS}</div>
                                        </div>
                                        <div class="jfk-bubble-arrow-id jfk-bubble-arrow jfk-bubble-arrowright" style="top: 20px;">
                                          <div class="jfk-bubble-arrowimplbefore"></div>
                                          <div class="jfk-bubble-arrowimplafter"></div>
                                        </div>
                                      </div>
                                </div>
                            </div>
                    </div>
                    <div class="list-form cf">
                        <div class="col-sm-12 col-md-12 form-group">
                            <div class="flat-checkbox">
                                <input type="checkbox" id="terms_conditions" name="terms_conditions" value="y">
                                <label for="terms_conditions">{TERMS_CONDITIONS_FIRST} %TERMS_CONDITIONS_LINK% {TERMS_CONDITIONS_THIRD} {SITE_NM} %PRIVACY_LINK%</label>
                                <label id="terms_conditions-error" class="error" for="terms_conditions"></label>
                            </div>
                        </div>
                    </div>
                    <div class="list-form cf">
                        <div class="col-sm-12 col-md-12 form-group">
                            <div class="captcha-img">
                              <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                              <div class="g-recaptcha" data-sitekey="{GOOGLE_CAPTCHA_SITE_KEY}"></div>
                              <label for="hiddenRecaptcha" generated="true" class="error" style="display:none"></label>
                            </div>
                        </div>
                    </div>
                    <div class="list-form cf">
                    <div class="col-sm-12 form-group">
                        <button type="submit" name="signup" id="signup" class="blue-btn" disabled="disabled">{LBL_SIGNUP}</button>
                        <em>{LBL_SIGNUP_ACCOUNT} <a href="javascript:void(0)" class="move-login">{LBL_LOGIN_BUTTON_SIGNIN}</a></em>
                    </div>

                    </div>
                    <div class="list-form cf">
                        <div class="social-icons signup-social cf">
                        <div class="fb-link">

                            <a href="javascript:void(0);" onclick="login();" class="fb-btn" title="{LBL_LOGIN_WITH_FACEBOOK}">
                              <small class="fb-icon"><i class="fa fa-facebook"></i></small>
                              <p>{LBL_FACEBOOK}</p>
                            </a>
                        </div>
                        <div class="linkedin-link">

                            <a href="{SITE_URL}signin/linkedin" class="fb-btn linkedin-btn loginWithSocialMedia" title="{LBL_LINKED_TITLE}">
                              <small class="linkedin-icon"><i class="fa fa-linkedin"></i></small>
                              <p>{LBL_LINKEDIN}</p>
                            </a>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>
  	</div>
</div>
<div class="home home-new clearfix">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
      </div>
      <div class="col-sm-6">
       
      </div>
    </div>
  </div>
</div>
<div class="home-main"></div>

<script type="text/javascript" src="{SITE_JS}guest-home-script.min.js?v=1.1"></script>
<script>
$(document).ready(function() {
    $(".toggle-password").click(function() {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
	$("#signup").removeAttr('disabled');

    $(".signup-hide").hide();
	if('<?php echo isset($_GET['action']) ? $_GET['action'] : '' ?>'=='signin_new'){
		$(".signup-hide").show();
      	$(".login-hide").hide();

        $('.anime-slide').animate({
        'marginLeft' : "0" //moves left
        });

        $('.slide-outer-bx').animate({
        'marginLeft' : "100%" //moves right
        });
	}
	
    $('.move-login').click(function() {
      $(".signup-hide").show();
      $(".login-hide").hide();

        $('.anime-slide').animate({
        'marginLeft' : "0" //moves left
        });

        $('.slide-outer-bx').animate({
        'marginLeft' : "100%" //moves right
        });
    });

    $('.move-signup').click(function() {
      
      $(".signup-hide").hide();
      $(".login-hide").show();
      
        $('.anime-slide').animate({
        'marginLeft' : "50%" //moves right
        });

        $('.slide-outer-bx').animate({
        'marginLeft' : "0" //moves right
        });
    });
});

/*$(document).ready(function() {
	var match_url = <?php echo URL_TO_MATCH_SIGNUP;?>;
	if(window.location.href.indexOf(match_url) > -1){
	}else{
		// $('.anime-slide').animate({
  //       'marginLeft' : "0" //moves left
  //       });

  //       $('.slide-outer-bx').animate({
  //       'marginLeft' : "100%" //moves right
  //       });
	}
});*/
</script>
<script type="text/javascript">
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