    <h3>{CHANGE_PSW}</h3>
    <div class="cp-outer-bx cf">
    <form class="" name="change_password_form" id="change_password_form" action="<?php echo SITE_URL; ?>change-password" method="post">
        <div class="col-sm-4 col-md-4 form-group cf">
            <label>{CUT_PSW}<sup>*</sup></label>
            <input type="password" id="old_password" name="old_password" placeholder="{ENT_CUT_PSW}" class="remove_psw"/>
        </div>

        <div class="col-sm-4 col-md-4 form-group cf">
            <label>{NEW_PSW}<sup>*</sup></label>
            <input type="password" id="password" name="password" placeholder="{ENT_NEW_PSW}" class="remove_psw"/>
        </div>

        <div class="col-sm-4 col-md-4 form-group cf">
            <label>{CON_PSW}<sup>*</sup></label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="{ENT_CON_PSW}" class="remove_psw" />
        </div>

        <div class="col-sm-12 form-group text-left">
            <button type="submit" class="blue-btn" name="change_password" id="change_password" disabled="disabled">
                {CHANGE_PSW}
            </button>
        </div>
    </form>
    </div>
