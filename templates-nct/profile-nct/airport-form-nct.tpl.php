<form method="post" name="add_edit_airport_form" id="add_edit_airport_form" action="<?php echo SITE_URL; ?>add-edit-airport">
  <input type="hidden" name="airport_id" id="airport_id" value="%AIRPORT_ID_ENCRYPTED%">
  <div class="form-list cf">
    <div class="col-sm-12">
      <div class="form-group cf">
        <input type="text" name="airport_id1" id="airport_id1" placeholder="{SELECT_CLOSEST_AIRPORT}*" value="%AIRPORT_NAME%" autocomplete="off" />
        <input type="hidden" name="airport_id_hidden" id="airport_id_hidden" value="%AIRPORT_ID%"/>
      </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group text-center cf" id="request_btn">
          <button type="submit" class="blue-btn" name="add_airport_multiple" id="add_airport_multiple">{BTN_AIRPORT_ADD}</button>
          <div class="space-mdl"></div>
          <input type="reset" class="outer-red-btn" name="airport_form_cancel" id="airport_form_cancel" data-dismiss="modal" value="{BTN_AIRPORT_CANCEL}"/>
        </div>
    </div>
  </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        var autocomp_opt = {
        source: function (request, response) {
            var input = this.element;
            $.ajax({
                url: "<?php echo SITE_URL; ?>getAirports1",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getClosestAirport',
                    airport_identifier: request.term
                },
                success: function (data) {
                    console.log(data);
                    if (data.length === 0) {
                        var r= $('<button type="submit" class="blue-btn" name="request_for_addition" id="request_for_addition"><?php echo BTN_REQUEST_FOR_ADDITION;?></button><input type="hidden" name="requested_airport_name" id="requested_airport_name" value="'+request.term+'" />');
                        $("#request_btn").html(r);
                    }else{
                        var r1 = $('<button type="submit" class="blue-btn" name="add_airport_multiple" id="add_airport_multiple"><?php echo BTN_AIRPORT_ADD;?></button><div class="space-mdl"></div><input type="reset" class="outer-red-btn" name="airport_form_cancel" id="airport_form_cancel" data-dismiss="modal" value="<?php echo BTN_AIRPORT_CANCEL;?>"/>');
                        $("#request_btn").html(r1);
                        response($.map(data, function (item) {
                            return {label: item.airport_identifier, value: item.airport_identifier, id: item.airport_id};
                        }));
                    }
                },
                error: function (jq, status, message) {
                }
            });
        },
        select: function (event, c) {
            airport_id = c.item.id;
            $("#airport_id_hidden").val(airport_id);
        },
        autoFocus: true
    };
        $("#airport_id1").autocomplete(autocomp_opt);
        $("#add_airport_multiple").prop('disabled', true);
    });
    $(document).on('click','#request_for_addition',function(){
        var airport_name = $('#requested_airport_name').val();
        if(airport_name != ''){
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>requestForAirportAddition",
                data: {
                    action: 'requestAirportAddition',
                    requested_airport_name: airport_name
                },
                beforeSend: function() {
                    addOverlay();
                },
                complete: function() {
                    removeOverlay();
                },
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    if (data.status == "true") {
                        toastr['success'](data.message);
                        $("#airport_container").show();
                        $(".edit-airport-container").html("");
                        $("#airport_container").html(obj.airports);
                        $("#add_airport").show();
                        $("#add_airport_container").hide();
                        height = $("#airport_main").offset().top;
                        scrolWithAnimation(height);
                        return false;
                    } else {
                        toastr['error'](data.message);
                    }

                }
            });
        }else{
            toastr['error']("{ERROR_AIRPORT_NAME_IS_EMPTY}");
        }
    });
    $(document).on('click', "#airport_form_cancel", function() {
        $("#airport_container").show();
        var add_skill_container = $("#add_airport_container");
        add_skill_container.fadeOut(1500, function() {
            add_skill_container.html("");
            $("#add_airport").show();
        });
    });
    $("#add_edit_airport_form").validate({
        rules: {'airport_id1': {required: true}},
        messages: {'airport_id1': {required: "&nbsp; {PLZ_AIRPORT_ENTER}."}},
        submitHandler: function(form) {return true;}
    });
    $("#add_edit_airport_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            console.log(obj);
            if (obj.status) {
                toastr["success"](obj.success);
                location.reload(true);
                //$('#add_airport').parent().addClass('hide');
                $("#airport_container").show();
                $(".edit-airport-container").html("");
                $("#airport_container").html(obj.airports);
                $("#add_airport").show();
                $("#add_airport_container").hide();
                height = $("#airport_main").offset().top;
                scrolWithAnimation(height);
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
    $(".multiple-airports").select2({
          ajax: {
            url: "<?php echo SITE_URL; ?>getAirports",
            dataType: 'json',
            delay: 250,
            type:'post',
            data: function (params) {
              return {
                airport_name: params.term, // search term
                airport_id: "'"+$(".multiple-airports").val()+"'", // search term
                action: 'getAirports' // search term
              };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.airport_id_orig, text: obj.airport_name };
                    })
                };
            },
            cache: true
          },    
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1
    });
    $(document).on('select2:select', "#airport_id", function(e) {    
        if($(".multiple-airports").val() != null){
            $("#add_airport").prop('disabled', false);
        }else{
            $("#add_airport").prop('disabled', true);
        }
    });
    $(document).on('select2:unselect', "#airport_id", function(e) {            
        if($(".multiple-airports").val() != null){
            $("#add_airport").prop('disabled', false);
        }else{
            $("#add_airport").prop('disabled', true);
        }
    });
</script>