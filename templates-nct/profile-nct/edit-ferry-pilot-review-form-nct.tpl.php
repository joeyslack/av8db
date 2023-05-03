 <form id="edit_ferry_pilot_rate_review_form" method="post" action="<?php echo SITE_URL; ?>profile/" name="edit_ferry_pilot_rate_review_form">
  <div class="rate">
    <span data-score="3" data-name="org-rating" id="org_rating"></span>
    <input type="hidden" name="rating" id="rating" value="%RATING%">
  </div>
  <div class="clearfix"></div>
  <div class="comp-desc-in pt-0">
      <div class="">
          <textarea placeholder="Description" name="description" id="description">%REVIEW%</textarea>
      </div>
      <input type="hidden" name="rate_id" id="rate_id" value="%ID%">
      <input type="hidden" name="sender_id" id="sender_id" value="%SENDER_ID%">
      <input type="hidden" name="receiver_id" id="receiver_id" value="%RECEIVER_ID%">
      <div class="form-group cf">
          <button type="submit" class="blue-btn" name="edit_rate_review" id="edit_rate_review">{LBL_COMPANY_DETAILS_RATE_REVIEW_SAVE} </button>
          <button type="reset" class="blue-btn" name="cancel_edit_rate_review" id="cancel_edit_rate_review">{LBL_COMPANY_DETAILS_RATE_REVIEW_CANCEL} </button>
      </div>
  </div>
</form>
<script type="text/javascript">
  $.validator.addMethod("companyNm", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\'\s]*$/.test(value);
    },"{ERROR_MESSAGE_FOR_FERRY_PILOT_VALID_RATE_REVIEWS}");
  $("#edit_ferry_pilot_rate_review_form").validate({
        ignore: [],
        rules: {
            description: {
                required: true,
                companyNm: true
            }
        },
        messages: {
            description: {
                required: "{ERROR_MESSAGE_FOR_COMPANY_REVIEW_DESCRIPTION}"
            }
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
            if ($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        }
    });
    $("#edit_ferry_pilot_rate_review_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
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