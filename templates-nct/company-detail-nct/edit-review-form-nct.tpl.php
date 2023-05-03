 <form id="edit_rate_review_form" method="post" action="<?php echo SITE_URL; ?>company/%COMPANY_ID%" name="edit_rate_review_form">
  <div class="rate">
    <span data-score="3" data-name="org-rating" id="org_rating"></span>
    <input type="hidden" name="rating" id="rating" value="%RATING%">
      <!-- <input type="radio" id="star1" name="rate" value="5" />
      <label for="star1" title="text">1 star</label>
      <input type="radio" id="star2" name="rate" value="4" />
      <label for="star2" title="text">2 stars</label>
      <input type="radio" id="star3" name="rate" value="3" />
      <label for="star3" title="text">3 stars</label>
      <input type="radio" id="star4" name="rate" value="2" />
      <label for="star4" title="text">4 stars</label>
      <input type="radio" id="star5" name="rate" value="1" />
      <label for="star5" title="text">5 stars</label> -->
  </div>
  <div class="clearfix"></div>
  <div class="comp-desc-in pt-0">
      <div class="">
          <textarea placeholder="Description" name="description" id="description">%REVIEW%</textarea>
      </div>
      <input type="hidden" name="company_id" id="company_id" value="%COMPANY_ID%">
      <input type="hidden" name="user_id" id="user_id" value="%BOOKING_ID%">
      <div class="form-group cf">
          <button type="submit" class="blue-btn" name="edit_rate_review" id="edit_rate_review">{LBL_COMPANY_DETAILS_RATE_REVIEW_SAVE} </button>
          <button type="reset" class="blue-btn" name="cancel_edit_rate_review" id="cancel_edit_rate_review">{LBL_COMPANY_DETAILS_RATE_REVIEW_CANCEL} </button>
      </div>
  </div>
</form>
<script type="text/javascript">
    $.validator.addMethod("companyNm", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\.,!$'\s]*$/.test(value);
    }, "{ERROR_MESSAGE_FOR_COMPANY_VALID_REVIEW_DESCRIPTION}");
    $("#edit_rate_review_form").validate({
        ignore: [],
        rules: {
            description: {required: true,companyNm: true}
        },
        messages: {
            description: {required: "{ERROR_MESSAGE_FOR_COMPANY_REVIEW_DESCRIPTION}"}
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
    $("#edit_rate_review_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
           if (obj.status == "suc") {
                toastr["success"](obj.message);
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.message);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    $(document).on('click','#cancel_edit_rate_review',function(){
        $('#GiveReview').modal('hide');
    });
</script>