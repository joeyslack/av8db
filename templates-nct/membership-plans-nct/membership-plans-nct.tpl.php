<div class="inner-main">
    <div class="membership-sec cf">
        <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-1"></div>
                    <div class="col-sm-12 col-md-12 col-lg-10">
                        <div class="gen-wht-bx mem-pro-bx cf">
                            <div class="conn-outer-tbl">
                                <div class="connection-img">
                                    <div class="membr-img">
                                    <?php echo getImageURL("user_profile_picture", $_SESSION['user_id'], "th3"); ?>
                                    </div>
                                </div>
                                <div class="conn-dtl">
                                    <h3>%USER_NAME_FULL%</h3>
                                    <div class="member-nm %CLASS_HIDE%">{LBL_INMAILS_OUTSTANDING} <em>%PENDING_INMAIL%</em></div>
                                    <div class="member-nm %CLASS_HIDE%">{LBL_REMAINING_DAYS} <em>%REMAINING_DAYS%</em></div>
                                </div>
                            </div>
                            <div class="manage-del-bx cf">
                                <?php
                                echo $this->adhoc_inmail_form;
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                                echo $this->membership_plans;
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-1"></div>
                </div>
            <div class="full-width">
                
            </div>
        </div>
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>
<div class="modal" id="detail_plan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close val_video" data-dismiss="modal" aria-label="Close">
            <i class="icon-close"></i>
        </button>
        <h4 class="modal-title" id="myModalLabel">{LBL_PAYMENT_SUMMARY}</h4>
      </div>
      <div  class="modal-body">
      <div id="data_pass"></div>
           

      </div>
       
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    $(document).on("click", "#plan_id_sub", function() {
        var plan_id=$(this).data('id');
        $.ajax({
            type: 'POST',
            url: '<?php echo SITE_URL ;?>modules-nct/membership-plans-nct/subscribe-plan-nct.php?plan_id='+plan_id,
            data:{'action':'plandetail','sess_user_id': '<?php echo $_SESSION['user_id']; ?>','plan_id': plan_id},
            dataType: 'json',
            success: function(data) {
               // console.log(data);
                if (data) {
                    $("#detail_plan div.modal-body").find("div#data_pass").html(data);
                    $("#detail_plan").modal('show');
                    
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
});
$(document).ready(function() {

    $(document).on("click", "#purchase_adhoc_inmails", function() {
        var plan_id=$("#planid_get").val();
        var no_of_inmails=$("#no_of_inmails").val();
        if(no_of_inmails != ''){

         $.ajax({
            type: 'POST',
            url: '<?php echo SITE_URL ;?>modules-nct/membership-plans-nct/purchase-adhoc-inmails-nct.php',
            data:{action:'adhoc_inmail_form',no_of_inmails:no_of_inmails},
            dataType: 'json',
            success: function(data) {
               // console.log(data);
                if (data) {
                    $("#detail_plan div.modal-body").find("div#data_pass").html(data);
                    $("#detail_plan").modal('show');
                    
                } else {
                    toastr['error'](data.error);
                }
            }
         });
       }else{
        toastr['error']("{LBL_ENTER_NO_OF_EMAIL}");
       }
    });
});
</script>