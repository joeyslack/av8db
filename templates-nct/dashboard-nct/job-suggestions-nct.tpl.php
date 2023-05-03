<li class="info-cell text-center">
    <div class="suggest-comp-bx jobs-tab-bx">
        <div class="in-close">
            <a class="close_job_suggestion" href="javascript:void(0);" title="{LBL_DELETE}">
                <i class="icon-close"></i>
            </a>
        </div>
        <div class="followin-pro-img">
            <a href="%COMPANY_URL%" title="%COMPANY_NAME%" class="company-logo in-img-70 ">%COMPANY_LOGO_URL% </a>
        </div>

        <h2><a href="%JOB_URL%" title="%JOB_TITLE%" class="orange-text">%JOB_TITLE%</a></h2>
        <h4><a class="blue-color" title="%COMPANY_NAME%" href="%COMPANY_URL%">%COMPANY_NAME% </a></h4>
        <h5>%INDUSTRY_NAME%</h5>
        <h6><a href="%JOB_URL%" title="%JOB_CATEGORY%" class="blue-color">%JOB_CATEGORY%</a></h6>
        <p>%LOCATION%</p>
        <p>{LBL_POSTED} %POSTED_DATE%</p>
        <div class="emp-bx">{LBL_REQUIRED_EXPERIENCE}<small>%REQUIRED_EXP_FROM%</small></div>
        <div class="emp-bx %HIDE_SKILL%">{LBL_SKILLS}<p>%SKILLS%</p></div>
                 %FEATURED%

        <div class="emp-bx">{LBL_LAST_DATE} <small>%LAST_DATE% (%LAST_DATE_REMAINING%)</small></div>
        <div class="view-more-bx">
        <?php echo $this->apply_url; ?>
        </div>
    </div>
</li>   