<form id="reset_pass_form" name="reset_pass_form" action="" method="post">
    <div class="form-group">
        <label>{ENT_NEW_PSW} :</label>
        <div class="input-group">
            <div class="input-group-addon textbox_prefix"><i class="fa fa-lock"></i></div>
            <input type="password" class="form-control" id="txt_pass1" name="txt_pass1" placeholder="{ENT_NEW_PSW}" />
        </div>
    </div>
    
    <div class="form-group">
        <label>{CON_PSW} :</label>
        <div class="input-group">
            <div class="input-group-addon textbox_prefix"><i class="fa fa-lock"></i></div>
            <input type="password" class="form-control" id="txt_pass2" name="txt_pass2" placeholder="{CON_PSW}">
            <input name="token" type="hidden" id="token" value="<?php print $this->hidd; ?>" />
        </div>
    </div>

    <input type="submit" name="reset_password" id="reset_password" class="btn btn-default btn-block btn_grey" value="Reset Password" />
</form>
