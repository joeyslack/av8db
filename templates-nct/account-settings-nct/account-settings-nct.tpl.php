<div class="inner-main ">
    <div class="setting-sec cf">
        <div class="container">
            <div class="col-sm-12 col-md-1"></div>
            <div class="col-sm-12 col-md-10">
                <div class="gen-wht-bx in-heading cf">
                        <?php echo $this->change_password_form; ?>
                        <?php echo $this->notification_settings; ?>
                </div>
            </div>
            <div class="col-sm-12 col-md-1"></div>
        	<div class="width-24 fade fadeIn">
                
            </div>
            <div class="width-76 fade fadeIn">
                
            </div>
            
        </div>
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#change_password").removeAttr('disabled');
    });



    $("#change_password_form").validate({
        rules: {
            old_password: {
                required: true,
                minlength: 6

            },
            password: {
                required: true,
                minlength: 6,
                
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: "{MSG_ENTER_PSW_OLD}",
                minlength: "{MSG_PSW_MIN}"


            },
            password: {
                required: "{MSG_ENTER_PSW_NEW}",
                minlength: "{MSG_PSW_MIN}",
               
            },
            confirm_password: {
                required: "{PLZ_CONFIRM_PSW}",
                equalTo: "{PSW_NOT_MATCH}"
            }
        }
    });

    $("#change_password_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"](obj.success);
                //window.location.href = '' + obj.redirect_url + '';
                $(".remove_psw").val('');
            } else {
                toastr["error"](obj.error);
                $(".remove_psw").val('');

            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });

    $(document).on("switchChange.bootstrapSwitch", ".notification_checkbox", function (event, state) {
        //console.log(this); // DOM element
        //console.log(event); // jQuery event
        //console.log(state); // true | false

        if (state) {
            var column_value = 'y';
        } else {
            var column_value = 'n';
        }

        var column_name = this.name;
        $.ajax({
            url: "update-account-settings",
            data: "action=update_account_settings&column_name=" + column_name + '&column_value=' + column_value,
            type: "POST",
            dataType: "json",
            success: function (response) {
                if ('' != response.operation_status && '' != response.message) {
                    toastr[response.operation_status](response.message);
                } else {
                    toastr['error']('{ERROR_VALIDATION_ACCOUNT_SETTINGS}');
                }
            }
        });

        return false;
    });
</script>
