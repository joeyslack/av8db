<form method="post" name="add_edit_skill_form" id="add_edit_skill_form" action="<?php echo SITE_URL; ?>add-edit-skill">
  <input type="hidden" name="skill_id" id="skill_id" value="">
  <div class="form-list cf">
    <div class="col-sm-12">
      <div class="form-group cf">
        <select name="skill_id[]" id="skill_id" class=" js-example-basic-multiple multiple-skills " multiple="multiple" style="width:100%;">
        </select>
      </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group text-center cf">
          <button type="submit" class="blue-btn" name="add_skill_multiple" id="add_skill">{BTN_SKILL_ADD}</button>
          <div class="space-mdl"></div>
          <input type="reset" class="outer-red-btn" name="skill_form_cancel" id="skill_form_cancel" data-dismiss="modal" value="{BTN_SKILL_CANCEL}"/>
        </div>
    </div>
  </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $("#add_skill").prop('disabled', true);
    });
    $(document).on('click', "#skill_form_cancel", function() {
        $("#skills_container").show();
        var add_skill_container = $("#add_skill_container");
        add_skill_container.fadeOut(1500, function() {
            add_skill_container.html("");
            $("#add_skills").show();
        });
    });
    $("#add_edit_skill_form").validate({
        rules: {'skill_id[]': {required: true}},
        messages: {'skill_id[]': {required: "&nbsp; {PLZ_SKILL_ENTER}."}},        
        submitHandler: function(form) {return true;}
    });
    $("#add_edit_skill_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"](obj.success);
                $("#skills_container").show();
                $(".multiple-skills").empty().trigger('change');
                $("#add_edit_skill_form")[0].reset();
                $("#add_edit_skill_form").hide();

                $("#skills_container").html(obj.skills);
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
    $(".multiple-skills").select2({
          ajax: {
            url: "<?php echo SITE_URL; ?>getSkills",
            dataType: 'json',
            delay: 250,
            type:'post',
            data: function (params) {
              return {
                skill_name: params.term, // search term
                skill_id: "'"+$(".multiple-skills").val()+"'", // search term
                action: 'getSkills' // search term
              };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.skill_id_orig, text: obj.skill_name };
                    })
                };
            },
            cache: true
          },    
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1
    });
    $(document).on('select2:select', "#skill_id", function(e) {    
        if($(".multiple-skills").val() != null){
            $("#add_skill").prop('disabled', false);
        }else{
            $("#add_skill").prop('disabled', true);
        }
    });
    $(document).on('select2:unselect', "#skill_id", function(e) {            
        if($(".multiple-skills").val() != null){
            $("#add_skill").prop('disabled', false);
        }else{
            $("#add_skill").prop('disabled', true);
        }
    });
</script>