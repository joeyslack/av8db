<div class="container">
        <div class="summary-bx form-group cf">
            <p><?php echo LBL_PAYPAL_REDIRECT_MEMBERSHIP;?></p>
            <ul class="in-mem-pln-view">
                <li>
                    <span><?php echo LBL_PLAN_NAME;?></span>
                    <small>%PLAN_NAME%</small>
                </li>
                <li>
                    <span><?php echo LBL_AMOUNT;?></span>
                    <small>%PRICE%</small>
                </li>
                <li>
                    <span><?php echo LBL_NO_OF_INMAILS;?></span>
                    <small>%NO_OF_INMAILS%</small>
                </li>
                <li>
                    <span><?php echo LBL_PLAN_DURATION;?></span>
                    <small>%PLAN_DURATION%</small>
                </li>
            </ul>
        </div>
            <form method="post" id="plan" action="<?php echo SITE_URL;?>modules-nct/membership-plans-nct/subscribe-plan-nct.php?plan_id=%PLAN_ID_ENCRYPTED%">
                <input type="hidden" name="plan_id" id="plan_id" value="%PLAN_ID_ENCRYPTED%" />
                <div class="form-group cf">
                    <button type="submit" class="blue-btn" name="subscribe" id="subscribe"><?php echo LBL_PROCEED_PAY;?>
                    </button>
                    <div class="space-mdl"></div>
                    <a href="%MEMBERSHIP_PLAN_URL%" class="outer-blue-btn cancel-btn cancel-experience-form" title="<?php echo LBL_PLANS;?>"><?php echo LBL_PLANS;?>
                    </a>
                </div>
            </form>
</div>
