<div class="jobs-out-bx my-groups ">
    <div class="srch-img">
     <div class="in-img-85">%GROUP_LOGO_URL% </div>
     </div>
     <div class="comp-rgt-info">
        <h3>%GROUP_NAME%
         <a href="javascript:void(0);" id="%GROUP_ID%" class="reportGroup %ISGROUPREPORTED% %ISGROUPOWNER%" title="{LBL_REPORT_GROUP}"><i class="fa fa-flag groupFlag" aria-hidden="true"></i></a></h3>
        <ol class="cate-ind-bx breadcrumb">
            <li>%GROUP_TYPE%</li>
        </ol>
        <div class="member-nm">%GROUP_MEMBERS_TEXT% <em>%GROUP_MEMBERS%</em></div>
     </div>
     <div id="join_leave_group_id" class="leave-gp">
        %JOIN_LEAVE_GROUP_HTML%
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<script type="text/javascript">
    $(document).on('click','.reportGroup',function(){
      var group_id = $(this).attr('id');
      var user_id = "<?php echo $_SESSION['user_id'];?>";
      if(group_id > 0){
          $.ajax({
             type: "POST",
             url: "<?php echo SITE_URL; ?>reportGroupPost",
             dataType:'json',
             data:{
                  'action':'reportGroupPost',
                  'group_id':group_id,
                  'user_id':user_id,
              },
             success: function(response)
             {    
              if (response.status == 'suc') {
                  toastr["success"](response.message);
                  window.location.href = '' + response.redirect_url + '';
              }else{
                  toastr["error"](response.message);
                  window.location.href = '' + response.redirect_url + '';
              }
             }
          });
      }  
  });
</script>