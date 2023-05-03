<div class="">
<div class="people-connect cf">
	<a href="%USER_URL%" title="%USER_NAME%" class="comment-img">%USER_IMAGE%</a>
	<div class="people-connect-nm">
	  <h4>
      	<a href="%USER_URL%" title="%USER_NAME%" class="blue-color">%USER_NAME% </a> 
      </h4>
  	  <!-- <p>%USER_HEAD_LINE%</p> -->
  	  <div class="in-close">
<!--       		<a href="javascript:void(0);" class="icon-close"></a>
 -->      		<div id="add_connection_url">
			  	<a href="javascript:void(0);" class="btn small-btn mrt10" id="add_connection" data-value="%ENCRYPTED_USER_ID%" title="{LBL_PEOPLE_YOU_KNOW_BUTTON}"><i class=" icon-follower"></i></a>
			  </div>
      	</div>
  	  
  	</div>
  
</div>
<div class="common-conn-list text-center cf ">
	<h4>%NO_OF_COMMON_CONNECTIONS% {LBL_COMMON_CONNECTIONS}</h4>
	<ul class="%HIDE_CONNECTION_CLS%">
	  <?php echo $this->common_connection; ?>
	</ul>
</div>
<div class="view-more-bx <?php echo $this->hidden_var; ?> "> <a href="%VIEW_ALL_LINK%" id="view_all_connection">{LBL_VIEW_ALL}<i class="icon-rgt-arrow"></i></a> </div>
</div>