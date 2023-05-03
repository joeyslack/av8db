<form method="post" name="add_edit_language_form" id="add_edit_language_form" action="{SITE_URL}add-edit-language">
    <input type="hidden" name="language_id" id="language_id" value="">
    <div class="form-list cf">
        <div class="col-sm-12">
            <div class="form-group cf">
                <select name="language_id[]" id="language_id" class="js-example-basic-multiple multiple-language " multiple="multiple" style="width:100%;"></select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group text-center cf">
                <button type="submit" class="blue-btn" name="add_language_multiple" id="add_language">{BTN_LANGUAGE_ADD} </button>
                <div class="space-mdl"></div>
                <input type="reset" class="outer-red-btn" name="language_form_cancel" id="language_form_cancel" data-dismiss="modal" value="{BTN_LANGUAGE_CANCEL}" />
            </div>
        </div>
        <div class="col-sm-3 col-md-offset-3">
            <div class="form-group"></div>
        </div>
        <div class="col-sm-3">
            <div class="form-group"></div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("#add_language").prop('disabled', true);
    });    
    $(document).on('click', "#language_form_cancel", function() {
        $("#languages_container").show();
        var add_language_container = $("#add_language_container");
        add_language_container.fadeOut(1500, function() {
            add_language_container.html("");
            $("#add_languages").show();
        });
    });
    $("#add_edit_language_form").validate({
        rules: {'language_id[]': {required: true}},
        messages: {'language_id[]': {required: "&nbsp; {ERROR_ENTER_LANGUAGE}"}},
        submitHandler: function(form) {return true;}
    });
    $("#add_edit_language_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                $("#languages_container").show();
                toastr["success"](obj.success);
                $(".multiple-language").empty().trigger('change');
                $("#add_edit_language_form")[0].reset();
                $("#add_edit_language_form").hide();
                $("#add_languages").show();
                $("#languages_container").html(obj.languages);
                return false;
            } else {
                toastr["error"](obj.error);
                return false;
            }
            return false;
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    $(".multiple-language").select2({
          ajax: {
            url: "{SITE_URL}getLanguages",
            dataType: 'json',
            delay: 250,
            type:'post',
            data: function (params) {
              return {
                language: params.term, // search term
                language_id: "'"+$(".multiple-language").val()+"'", // search term
                action: 'getLanguages' // search term
              };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.language_id_orig, text: obj.language };
                    })
                };
            },
            cache: true
          },    
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1
    });
    $(document).on('select2:select', "#language_id", function(e) {    
        if($(".multiple-language").val() != null){
            $("#add_language").prop('disabled', false);
        }else{
            $("#add_language").prop('disabled', true);
        }
    });
    $(document).on('select2:unselect', "#language_id", function(e) {            
        if($(".multiple-skills").val() != null){
            $("#add_language").prop('disabled', false);
        }else{
            $("#add_language").prop('disabled', true);
        }
    });
</script>