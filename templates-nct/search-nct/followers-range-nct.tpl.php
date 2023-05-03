<div id="%FILTER_TYPE%no_of_followers_filters_container" class="search-box search-filters mCustomScrollbar %JOB_CATEGORIES_FILTER_HIDDEN%">
    <h5>{LBL_NO_OF_FOLLOWERS}</h5>
    <input type="text" id="no_of_followers_range" name="no_of_followers_range" value="" />
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <input type="number" name="min_no_of_followers" id="min_no_of_followers" value="%MIN_NO_OF_FOLLOWERS%" min='0' />
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <input type="number" name="max_no_of_followers" id="max_no_of_followers" value="%MAX_NO_OF_FOLLOWERS%" min='0' />
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#no_of_followers_range").ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: 0,
            max: <?php echo MAX_NO_OF_MEMBERS_RANGE_SLIDER; ?>,
            from: $("#min_no_of_followers").val(),
            to: $("#max_no_of_followers").val(),
            type: 'double',
            step: 1,
            prefix: "",
            grid: true,
            onStart: function (data) {
                from = data.from;
                to = data.to;
                $("#min_no_of_followers").val(data.from);
                $("#max_no_of_followers").val(data.to);
            },
            onChange: function (data) {
                from = data.from;
                to = data.to;
                $("#min_no_of_followers").val(data.from);
                $("#max_no_of_followers").val(data.to);
            },
            onFinish: function (data) {
                from = data.from;
                to = data.to;
                $("#min_no_of_followers").val(data.from);
                $("#max_no_of_followers").val(data.to);
            },
            onUpdate: function (data) {
                from = data.from;
                to = data.to;
                $("#min_no_of_followers").val(data.from);
                $("#max_no_of_followers").val(data.to);
            }
        });
    });
</script>