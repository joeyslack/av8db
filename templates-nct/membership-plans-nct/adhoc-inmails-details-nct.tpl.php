<div class="container">
    <div class="page-content-main">
        <div class="page-content form-group cf summary-bx">
            <p><?php echo LBL_PAYPAL_REDIRECT_MEMBERSHIP;?> </p>
            <ul class="in-mem-pln-view">
                <li>
                    <strong><?php echo LBL_UNIT_PRICE;?> :</strong> %UNIT_PRICE%
                </li>
                <li>
                    <strong><?php echo LBL_NO_OF_INMAILS;?> :</strong> %NO_OF_INAMILS%
                </li>
                <li>
                    <strong><?php echo LBL_TOTAL_PRICE;?> :</strong> %TOTAL_PRICE%
                </li>
            </ul>

            <form action="<?php echo SITE_URL;?>modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php" method="post" name="adhoc_inmails_from" id="adhoc_inmails_from">
                <input type="hidden" name="no_of_inmails" id="no_of_inmails" value="%NO_OF_INAMILS%" />
                <div class="form-group cf">
                    <button type="submit" class="blue-btn" name="subscribe" id="subscribe"><?php echo LBL_PROCEED_PAY;?>
                    </button>
                    <div class="space-mdl"></div>
                    <a href="%MEMBERSHIP_PLAN_URL%" class="outer-blue-btn cancel-btn cancel-experience-form" title="<?php echo LBL_PLANS;?>"><?php echo LBL_PLANS;?>
                    </a>
                </div>
                
            </form>
            
        </div>
    </div>
</div>
