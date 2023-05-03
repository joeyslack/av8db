<div class="resbin-discription fade fadeIn">
    <p class="gray-text">
        %ADHOC_INMAIL_DESCRIPTION%
        <a href="javascript:void(0);" id="show_adhoc_inmails_form" class="blue-btn pull-right">
            <strong>{LBL_CLICK_HERE}</strong>
        </a>
    </p>
    <form method="post" name="adhoc_inmails_form" id="adhoc_inmails_form" action="" class="adhoc_inmails_form">
        <div class="row">
            <div class="col-sm-12 col-md-2">
                <label>{LBL_UNIT_PRICE}</label>
                <div class="adhoc_inmail_box">
                    %CURRENCY_SYMBOL%<span id="adhoc_inmails_unit_price">%ADHOC_INMAILS_UNIT_PRICE%</span>
                </div>
            </div>
            <div class="col-sm-12 col-md-2">
                <p>&nbsp;</p>
                <div class="adhoc_inmail_box no-borders">
                    <i class="icon-close orange-text"></i>
                </div>
            </div>
            <div class="col-sm-12 col-md-2">
                <label>{LBL_NO_OF_INMAILS}</label>
                <div class="adhoc_inmail_box">
                    <input type="text" name="no_of_inmails" id="no_of_inmails" class="form-control no_of_inmails positive-integer" placeholder="" value="" autocomplete="off" />
                </div>
            </div>
            <div class="col-sm-12 col-md-2">
                <p>&nbsp;</p>
                <div class="adhoc_inmail_box no-borders">
                   <i class="orange-text equal-line"></i>
                </div>
            </div>
            <div class="col-sm-12 col-md-2">
                <label>{LBL_TOTAL_PRICE}</label>
                <div id="total_price_container" class="adhoc_inmail_box">
                    %CURRENCY_SYMBOL%<span id="adhoc_inmails_total_price"></span>
                </div>
            </div>
            <!--<div class="col-sm-12 col-md-2">
                <input type="hidden" name="plan_id" value="%PLAN_ID%" id="planid_get">
                <p>&nbsp;</p>
                <div class="adhoc_inmail_box no-borders text-right">
                    <a href="javascript:void(0)" class="outer-blue-btn" name="purchase_adhoc_inmails" id="purchase_adhoc_inmails">
                        {LBL_PURCHASE}</a>
                     <button  class="outer-blue-btn" name="purchase_adhoc_inmails" id="purchase_adhoc_inmails">
                        {LBL_PURCHASE}
                    </button>
               </div>
            </div>-->  
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).on("click", "#show_adhoc_inmails_form", function() {
        /*$("#adhoc_inmails_form").fadeIn(1500, function() {
            $("#no_of_inmails").focus();
        });*/
        $("#adhoc_inmails_form").toggle(1500, function() {
            $("#no_of_inmails").focus();
        });
    });

    $(document).on("keyup", "#no_of_inmails", function() {
        calculateTotalPrice();
    });

    function calculateTotalPrice() {
        var adhoc_inmails_unit_price = parseFloat($("#adhoc_inmails_unit_price").html()).toFixed(2);
        var no_of_inmails = parseInt($("#no_of_inmails").val());

        var no_of_inmails_filtered = ((isNaN(no_of_inmails)) ? 0.00 : no_of_inmails);

        var adhoc_inmails_total_price = (adhoc_inmails_unit_price * no_of_inmails_filtered).toFixed(2);
        $("#adhoc_inmails_total_price").html(adhoc_inmails_total_price);
    }

    $(document).ready(function() {
        calculateTotalPrice();
    });
</script>
