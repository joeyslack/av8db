<div class="gen-wht-bx cf">
    <div class="compny-out-bx">
        <div class="">
            <a href="%COMPANY_PAGE_URL%" class="in-img-85" title="%COMPANY_NAME%">%COMPANY_LOGO_URL%</a>
        </div>
        <div class="comp-rgt-info">
            <h3>
                <a href="%COMPANY_PAGE_URL%" class="blue-color" title="%COMPANY_NAME%">%COMPANY_NAME%</a>
                <div class="rate-review rate-review-h">
                    <ul class="rate-bx mr-0">
                      <li><a href="%COMPANY_PAGE_URL%"><i class="fa fa-star"></i> (%COMPANY_RATING_TOTAL%)</a>
                      </li>
                    </ul>
                </div>
            </h3>
            <span>%COMPANY_INDUSTRY%</span>
             <div class="addr-bx"><i class="icon-email"></i>%OWNER_EMAIL_ADDRESS%</div>
        </div>
    </div>
    <div class="comp-view-info %HIDE_DESC%">
        <a href="%WEBSITE_OF_COMPANY%" target="_blank" title="%COMPANY_NAME%">%WEBSITE_OF_COMPANY%</a>
        <p class="word_wrap_data">%COMPANY_DESCRIPTION%</p>
    </div>   
    <div class="manage-del-bx cf"> 
        <div class="emp-bx">{LBL_MYC_EMPLOYEES} <small>%RANGE_OF_NO_OF_EMPLOYEES%</small></div> 
        <?php echo $this->company_actions; ?>
    </div>
</div>