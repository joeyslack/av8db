<div class="gen-wht-bx in-heading fade fadeIn">
	<h3>{LBL_JOINED_GROUP}</h3>
	<div id="joined_group_list">
	<?php echo $this->joined_groups; ?>
	<?php echo $this->view_all_link; ?>
	</div>
</div>
<script type="text/javascript">
	$(document).on("click", "#leave_group", function() {
		var group_id = $(this).data('group-id');
		var current_group_div = $(this).parents('.item');
		$.ajax({
			type: 'POST',
			url: "<?php echo SITE_URL; ?>removeJoinedGroup",
			data: {group_id: group_id,action: 'removeJoinedGroup'},
			beforeSend: function() {addOverlay();},
			complete: function() {removeOverlay();},
			dataType: 'json',
			success: function(data) {
				if (data.status) {
					toastr['success'](data.msg);
					window.location.reload();

					/*current_group_div.removeClass('active');
                    current_group_div.next('.item').addClass('active');
                    if(current_group_div.find('active').length == 0){
                        current_group_div.prev('.item').addClass('active');
                        console.log(current_group_div.find('item'));
                        
                    }
                
                    if($('.item').find('#leave_group').length == 1){
                            $("#joined_group_list").html(lang.ERROR_YOU_HAVE_NOT_JOINED_ANY_GROUP);
                        }
                    current_group_div.fadeOut(1000, function() {
                        $(this).remove();
                    });
					
					current_group_div.fadeOut(1000, function(){
						$(this).remove();
					});*/
				} else {
					toastr['error'](data.msg);
				}
			}
		});
	});
</script>