<li class="info-cell text-center">
	<div class="suggest-comp-bx grp-tab-bx">
		<div class="in-close">
			<a class="close_group_suggestion" href="javascript:void(0);" title="{LBL_DELETE}">
				<i class="icon-close"></i>
			</a>
		</div>
		<div class="followin-pro-img">
			<a href="%GROUP_URL%" title="%GROUP_NAME%" class="company-logo in-img-70">%GROUP_LOGO_URL%</a>
		</div>
		<h4><a class="blue-color" title="%GROUP_NAME%" href="%GROUP_URL%">%GROUP_NAME% </a> </h4>
    	<!-- <h5>%INDUSTRY_NAME%</h5> -->
    	<small class="purple-text">%GROUP_TYPE%</small>
    	<div class="member-nm">{LBL_DB_MEMBERS_SMALL}<em>%GROUP_MEMBERS%</em></div>
    	<div class="share-number"><span>{LBL_CONNECTED_MEMBERS}</span><em>%CONNECTED_MEMBERS%</em></div>
    	<div class="common-conn-list">
    		<div class="srch-person-dtl-grp">
    			<div class="grp-usr-img">%CREATOR_PROFILE_IMAGE%</div>
    			<div class="grp-dtl-info">
    				<h6>%CREATOR_NAME%</h6>
    			</div>
    		</div>
    	</div>
    	<div class="view-more-bx">
			<?php echo $this->joined_group_url; ?>
		</div>
    </div>
</li>