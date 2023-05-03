<!-- <div id="applied_for_jobs_carousel" class="carousel slide inner-slider " data-ride="carousel">
    <div class="carousel-inner" role="listbox">%APPLIED_FOR_JOBS_CAROUSEL_ITEMS%</div>

</div> -->
<div class="owl-carousel owl-theme" id="applied_for_jobs_carousel">
%APPLIED_FOR_JOBS_CAROUSEL_ITEMS%
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
        var totalItems = $('#applied_for_jobs_carousel').find('.owl-item').length;
        if(totalItems<=1){
                $('#applied_for_jobs_carousel').find(".owl-controls").attr("class","hidden");

        }
      }
</script>