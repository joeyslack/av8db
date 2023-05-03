<div class="right-part-main">
  <div class="fix-sidebar" data-spy="affix" data-offset-top="0" data-offset-bottom="30">
    <div class="gen-wht-bx cf">
    <div class="profile-view-outer">
       <img src="%COVER_IMG%" alt="img" class="banner_img_change">
        <div class="edt-bx  %CLASS_DIS%">
          <a href="%EDIT_PROFILE_URL%" title="{LBL_SUB_HEADER_EDIT_PROFILE}">
          <i class="icon-pencil"></i>
          </a>
        </div>
        <figure>
          <div class="profile-pic">%IMG%
          </div>
          <div class="pro-nm-addr">
              <h1>%USER_NAME_FULL%</h1>
              <!-- <p>%HEADLINE%</p> -->
              </div>
          </figure>

      <ul class="view-box">
          <li class="view-cell  %CLASS_DIS%">
            <span class="no-of-visitors-container purple-text">%NO_OF_VISITORS%</span>
            <p >{LBL_PERSONS_VIEWED_YOUR_PROFILE_IN_PAST_DAY}</p>
            <a href="javascript:void(0);" title="%NO_OF_VISITORS%{LBL_VISITORSIN_LASTDAYS}">  </a>
          </li>
          <li class="view-cell">
            <span><a href="%CONNECTIONS_URL%" class="orange-text" title="">%NO_OF_CONNECTIONS%</a></span>
            <p>{LBL_CONNECTIONS}</p>
            <p> <a href="%ADD_CONNECTION_URL%" class="blue-color" title="{LBL_ADD_NEW_CONNECTION}">{LBL_ADD_NEW_CONNECTION}</a> </p>
            <p> <a data-toggle="modal" data-target="#invite_friend" class="blue-color">{INVITE_FRIEND_LINK}</a> </p>
          </li>
        </ul>
    </div>
  </div>
  </div>
</div>
<div class="modal fade in modal-h" id="invite_friend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog  is-width-set" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
            <h4 class="modal-title" id="myModalLabel">{INVITE_FRIEND}</h4>
        </div>
        <form id="invite_friend_form" method="post" action="<?php echo SITE_URL; ?>dashboard">
          <div class="modal-body">
            <div class="form-list cf row">
                <div class="col-sm-12">
                    <div class="form-group cf">
                        <textarea placeholder="Enter Personalized Message" name="user_message" id="user_message"></textarea>
                    </div>
                    <div class="form-group cf">
                        <input type="text" placeholder="Enter Email Address" name="user_email" id="user_email">
                    </div>
                </div>
                <div class="form-group cf text-center">
                    <button type="submit" class="blue-btn" name="send_invitation" id="send_invitation">{SEND_INVITATION}</button>
                    <input type="reset" class="outer-red-btn" name="send_invitation_cancel" id="send_invitation_cancel" data-dismiss="modal" value="Cancel">
                </div>
            </div>
          </div>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#invite_friend').on('hidden.bs.modal', function() {
    var form_var = $('#invite_friend_form');
    form_var.validate().resetForm();
    form_var.find('.error').removeClass('error');
});
  $(document).on('click', ".close_job_suggestion", function() {
      closest_li = $(this).closest('li');
      closeJobSuggetion(closest_li);
  });
  $(document).on('click', ".close_company_suggestion", function() {
      closest_li = $(this).closest('li');
      closeCompanySuggetion(closest_li);
  });
  $(document).on('click', ".close_group_suggestion", function() {
      closest_li = $(this).closest('li');
      closeGroupSuggetion(closest_li);
  });
  $(document).on('click', ".close_people_you_know", function() {
      closest_li = $(this).closest('li');
      closest_li.fadeOut(500, function() {
          closest_li.remove();
      });
      if ($(".people_you_know_ul .people_you_know_li").length == 1) {
          $(".people_you_know_ul").html('<a href="<?php echo SITE_URL . "people-you-may-know" ?>" title="{LBL_VIEW_ALL}">{LBL_VIEW_ALL_SUGGESTIONS}</a>');
      }
  });
  $.validator.addMethod("companyNm", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\',.!?(){}*\s]*$/.test(value);
    }, "{INVITE_FRIEND_VALID_USER_MESSAGE}");
  $("#invite_friend_form").validate({
        ignore: [],
        rules: {
            user_message: {required: true, companyNm:true},
            user_email: {required: true,checkEmail: true}
        },
        messages: {
            user_message: {required: "{ERR_SEND_INVITATION_USER_MESSAGE}"},
            user_email: {required: "{ERR_SEND_INVITATION_USER_EMAIL}",checkEmail:"{ERR_SEND_INVITATION_USER_VALID_EMAIL}"}
        },
        highlight: function(element) {
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            if($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        }
    });
    $("#invite_friend_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"](obj.success);
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
  function closeJobSuggetion(closest_li) {
      closest_li.fadeOut(500, function() {
          closest_li.remove();
      });
      if ($(".job_suggetion_ul li").length == 1) {
          $(".job_suggetion_ul").html('{LBL_NO_SUGGESTIONS}');
      }
  }
  function closeCompanySuggetion(closest_li) {
      closest_li.fadeOut(500, function() {
          closest_li.remove();
      });
      if ($(".company_suggetion_ul li").length == 1) {
          $(".company_suggetion_ul").html('{LBL_NO_MORE_SUGGESTION}');
      }
  }
  function closeGroupSuggetion(closest_li) {
      closest_li.fadeOut(500, function() {
          closest_li.remove();
      });
      if ($(".group_suggetion_ul li").length == 1) {
          $(".group_suggetion_ul").html('{LBL_NO_MORE_GROUP_SUGGESTION}');
      }
  }
</script>