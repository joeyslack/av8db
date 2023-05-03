<!-- %STICKY_BUTTONS%
 --><header class="header-sec inner-header cf">
    <div class="header-top">
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="is-dotted-bx">
                        <a href="#" class="is-dotted-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                        </a>
                    </div>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"><span class="sr-only">{LBL_TOGGLE_NAV}</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                    <a class="navbar-brand" href="%HOMR_URL%" title="{SITE_NM}"><img src="https://storage.googleapis.com/av8db/site-images-nct/{SITE_LOGO}" alt="{SITE_NM}" /></a>
                </div>
                %HEADER_RIGHT%
            </div>
        </nav>
    </div>
</header>
<div class="lft-nav %CLASS_NAV_HIDE%">
 %NAVIGATION_BAR_AFTER_LOGIN%
 </div>
 <div class="rgt-nav %CLASS%">
    <div class="in-rgt-scroll">
     <ul class="areas">
        %MSG_LIST%
     </ul>
     </div>
     <div class="in-more">
        <a href="{SITE_URL}messaging/#message">{LBL_HEADER_VIEW_ALL_MESSAGES}</a>
     </div>
 </div>
<div class="clearfix"></div>
<script type="text/javascript">
    $(document).on("click",".search-entity-selection-li",function(){var selected_entty_class=$(this).find(".search-entity-selection i").attr("class");$("#search_selected_entity").attr("class",selected_entty_class);$("#selected_entity_container").attr("data-entity",$(this).find(".search-entity-selection").data('entity'));});
    $(document).on("submit","#header_search_form",function(e){e.preventDefault();var urlParam={};var search_entity=$("#selected_entity_container").attr("data-entity");if($("#keyword").val().trim()!=""){urlParam['keyword']=$("#keyword").val();}else{delete urlParam['keyword'];}var newurlParam=jQuery.extend({},urlParam);delete newurlParam.search_type;var newParam=decodeURIComponent($.param(newurlParam));if(newParam!=''){var url=SITE_URL+'search/'+search_entity+'?'+newParam;}else{var url=SITE_URL+'search/'+search_entity;}window.location=url;});
    $(document).on('click',"#approve_connection_header",function(){user_id = $(this).data('value');closest_li = $(this).closest('li');$.ajax({type:'POST',url:"{SITE_URL}approveConnection",data:{user_id:user_id,action:'approveConnection'},beforeSend:function(){addOverlay();},complete:function(){removeOverlay();},dataType:'json',success:function(data){if (data.status) {$("#connection_request_count").html($("#connection_request_count").html() - 1);if($("#connection_request_count").html()==0){$("#connection_request_count").addClass('hidden');}toastr['success'](data.msg);closest_li.fadeOut(500, function() {closest_li.remove();});}else{toastr['error'](data.msg);}}});});
    $(document).on('click',"#reject_connection_header",function(){user_id = $(this).data('value');closest_li = $(this).closest('li');$.ajax({type:'POST',url:"{SITE_URL}rejectConnection",data:{user_id:user_id,action:'rejectConnection'},beforeSend:function(){addOverlay();},complete:function(){removeOverlay();},dataType:'json',success:function(data){if(data.status){$("#connection_request_count").html($("#connection_request_count").html()-1);toastr['success'](data.msg);closest_li.fadeOut(500,function(){closest_li.remove();});}else{toastr['error'](data.msg);}}});});
    $("#message_list_container").mCustomScrollbar();
    function loadMoreMessages(url, showLoader, appendORReplace) {$.ajax({type: 'POST',url: url,beforeSend:function(){if(showLoader){addOverlay();}},complete:function(){if(showLoader){removeOverlay();}},dataType:'json',success: function(data){if("r"==appendORReplace){$("#all-message-list").html(data.content);}else{$("#all-message-list").find("li.load-more").remove();$("#all-message-list").append(data.content);}}});}
    $(document).on('click',"#show_notification",function(){$.ajax({url:"{SITE_URL}mark_notifications_as_read",type:"POST",dataType:"json",success:function(response){if (response.operation_status=='success'){$("#notifications_count").html(0);$("#notifications_count").addClass('hidden');}}});});
    $(document).ready(function(){if($("#notifications_count").html()==0){$("#notifications_count").addClass('hidden');}if($("#messages_count").html()==0){$("#messages_count").addClass('hidden');}if($("#connection_request_count").html()==0){$("#connection_request_count").addClass('hidden');}});
</script>