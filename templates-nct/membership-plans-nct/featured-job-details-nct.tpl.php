<div class="inner-main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-2"></div>
            <div class="col-sm-12 col-md-8">
                <div class="gen-wht-bx in-heading cf">
                    <h3>{LBL_PAYMENT_SUMMARY}</h3>
                    <div class="trans-sucess fade fadeIn cf">
                       <p>{LBL_PAYPAL_REDIRECT_MEMBERSHIP} </p>
                       <ul class="in-mem-pln-view">
                        <li>
                            <strong>{LBL_PLAN_NAME} :</strong>%PLAN_NAME%
                        </li>
                        <li>
                            <strong>{LBL_AMOUNT} :</strong>%PRICE%
                        </li>
                        <li>
                            <strong>{LBL_PLAN_DURATION} :</strong>%PLAN_DURATION%
                        </li>
                       </ul>
                       <div class="clearfix"></div>
                       <form action="" method="post">
                        <input type="hidden" name="plan_id" id="plan_id" value="%PLAN_ID_ENCRYPTED%" />
                        <input type="hidden" name="job_id" id="job_id" value="%JOB_ID_ENCRYPTED%" />
                            <div class="form-group cf">
                                <button type="submit" class="blue-btn" name="subscribe" id="subscribe">
                                    <i class="fa fa-arrow-circle-o-right"></i> {LBL_PROCEED_PAY}
                                </button>
                                <div class="space-mdl"></div>
                                <a href="%JOB_URL%" class="outer-blue-btn cancel-experience-form" title="{LBL_BACK_TO_JOBS}">
                                    <i class="fa fa-arrow-circle-o-left"></i> {LBL_BACK_TO_JOBS}
                                </a>
                            </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-2"></div>
        </div>
    </div>
</div>