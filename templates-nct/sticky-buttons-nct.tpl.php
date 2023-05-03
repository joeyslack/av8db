 %CONTACT_FORM% %FEEDBACK_FORM%
<script type="text/javascript">
    $(document).ready(function() {floating_form = $(".floating-form");width = floating_form.css('width');floating_form.css("right", "-" + width);});
    function toggleSideForm(element){width=element.css('width');if(element.hasClass('visiable')){element.animate({"right":"-"+width},{duration:300}).removeClass('visiable',function(){$(".floating-form").show();});}else{element.animate({"right":"0px"},{duration:300}).addClass('visiable');}}
    $(document).on("click","#contact_form_opener",function(){$("#feedback_form_container").hide();toggleSideForm($(this).parents(".floating-form"));});
    $(document).on("click","#feedback_form_opener",function(){$("#contact_form_container").hide();toggleSideForm($(this).parents(".floating-form"));});
</script> 