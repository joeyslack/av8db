<div class="gen-wht-bx in-heading fade fadeIn">
    <h3>{LBL_FOLLOWING_COMPANIES} </h3>
        <div id="following_company_list">
            <?php echo $this->following_companies; ?>
                <?php echo $this->view_all_link; ?>
        </div>
</div>

<script type="text/javascript">
    $(document).on("click", "#unfollow", function() {
        var company_id = $(this).data('company-id');
        var current_group_div = $(this).parents('.item');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>unfollowCompany",
            data: {company_id: company_id,action: 'unfollowCompany'},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success']("{SUCCESS_YOU_HAVE_SUCCESSFULLY_UNFOLLOWED_THE_COMPANY}");
                    window.location.reload();

                    /*current_group_div.removeClass('active');
                    current_group_div.next('.item').addClass('active');
                    if(current_group_div.find('active').length == 0){
                        current_group_div.prev('.item').addClass('active');
                        console.log(current_group_div.find('item'));
                        
                    }
                
                    if($('.item').find('#unfollow').length == 1){
                            $("#following_company_list").html(lang.ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY);
                        }
                    current_group_div.fadeOut(1000, function() {
                        $(this).remove();
                    });*/
                } else {
                    toastr['error']("{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}");
                }
            }
        });
    });

    $('.owl-carousel').owlCarousel({
        items:1,
        margin:10,
        nav:true,
        autoHeight:true,
        onInitialized: data_hide,

    });
    function data_hide(event) {
        var totalItems = $('#following_company_list').find('.owl-item').length;
        if(totalItems<=1){
                $('#following_company_list').find(".owl-controls").attr("class","hidden");

        }
      }
</script>