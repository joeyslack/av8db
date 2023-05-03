<div class="right-part-main">
      <div class="fadeIn">
            %MEMBERSHIP_PLAN%
       </div>
    %JOINED_GROUPS%
    
    %FOLLOWING_COMPANIES%
    %APPLIED_JOBS%
    
    %COMMON_CONNECTIONS%
    %SIMILAR_PROFILES%
  
    
</div>
<script type="text/javascript">
	$(document).on('click', "#add_connection", function() {
        user_id = $(this).data('value');
        closest_li = $(this).closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>addConnection",
            data: {
                user_id: user_id,
                action: 'addConnection'
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    //alert($(this).data('value'));
                    toastr['success'](data.msg);
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });
                    if ($(".similar_profiles li").length == 1) {
                        $(".similar_profiles").html('{NO_DATA_FOUND_MSG}');
                    }

                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
     $(document).on('click', ".close_similar_profile", function() {
        closest_li = $(this).closest('li');
        closest_li.fadeOut(500, function() {
            closest_li.remove();
        });
        if ($(".similar_profiles li").length == 1) {
            $(".similar_profiles").html('{NO_PFOFILE_FOUND_MSG}');
        }
    });

    window.onscroll = function() {myFunction()};
        
        var header = document.getElementById("applied-job-id");
        var sticky = header.offsetTop;
        function myFunction() {
          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
          } else {
            header.classList.remove("sticky");
          }
        }
</script>