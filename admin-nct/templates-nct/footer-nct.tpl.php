<div class="footer">
  <div class="footer-inner">
    <?php //echo $this->fotoerPanel;?>
    <table border="0" align="center" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="left">Copyright &copy; <?php echo date('Y'); ?>  <?php echo SITE_NM; ?>, All Rights Reserved.<br />
          Thank you for partnering with 
          <!-- <a href="http://www.ncrypted.com" rel="nofollow"  target="_blank">NCrypted</a>. <a href="http://www.ncrypted.com/contact" rel="nofollow" target="_blank">Request Support</a> -->
        </td>
        <td align="right">
          <!-- <a href="http://www.ncrypted.net/" rel="nofollow" target="_blank" title="Web Development Company"><img src="<?php echo SITE_ADM_IMG; ?>nct-logo.png" alt="Web Development Company" /></a> -->
        </td>
      </tr>
    </table>
  </div>
</div>

<script type="text/javascript">
  
  setInterval("getNotifications()", 10000); // Update every 10 seconds 
        function getNotifications() {
            $.ajax({
                url: "<?php echo SITE_ADM_MOD; ?>notifications-nct/ajax.notifications-nct.php",
                type: "POST",
                dataType: "json",
                data: {
                    action: 'get_admin_notifications'
                },
                success: function (response) {
                    if (response != null && response.operation_status && response.operation_status == 'success') {
                        $("#header_notification_bar .badge").removeClass("hidden");
                        
                        
                        new_counter = parseInt($("#header_notification_bar .badge").html()) + parseInt(response.notifications_count);
                        $("#header_notification_bar .badge").html(new_counter);

                        if (5 < $("#header_notification_bar .scroller li").size()) {
                            var remove_li = 5 - $("#header_notification_bar .scroller li").size();
                            $('#header_notification_bar .scroller li:not(:last)').slice(remove_li).remove();
                        }
                        $("#header_notification_bar .scroller").prepend(response.notifications);
                    }

                }
            });
        }
</script>
