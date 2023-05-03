<div id="joined_groups_carousel" class="owl-carousel owl-theme" >
%JOINED_GROUPS_CAROUSEL_ITEMS%
</div>

<script>
	$('.owl-carousel').owlCarousel({
        items:1,
        margin:10,
        nav: true,
        autoHeight:true,
		onInitialized: data_hide,

    });
    function data_hide(event) {
        var totalItems = $('#joined_group_list').find('.owl-item').length;
        if(totalItems<=1){
                $('#joined_group_list').find(".owl-controls").attr("class","hidden");

        }
      }
</script>