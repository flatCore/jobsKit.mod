<?php
/**
 * @Module jobsKit
 * ACP header injection
 */
 
if(!defined('FC_INC_DIR')) {
	die("No access");
}

include '../modules/jobsKit.mod/backend/functions.php';


?>

<link rel="stylesheet" href="../modules/jobsKit.mod/backend/css/acp.css" type="text/css">
<link rel="stylesheet" href="../modules/jobsKit.mod/backend/js/bootstrap-datetimepicker.min.css" />

<script type="text/javascript" src="../modules/jobsKit.mod/backend/js/moment.min.js"></script>
<script type="text/javascript" src="../modules/jobsKit.mod/backend/js/bootstrap-datetimepicker.min.js"></script>


<script type='text/javascript'>
	
	$.extend(true, $.fn.datetimepicker.defaults, {
    icons: {
      time: 'far fa-clock',
      date: 'far fa-calendar',
      up: 'fas fa-arrow-up',
      down: 'fas fa-arrow-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right',
      today: 'fas fa-calendar-check',
      clear: 'far fa-trash-alt',
      close: 'far fa-times-circle'
    }
  });
	
	$(function(){
	
		$('.dp').datetimepicker({
			timeZone: 'UTC',
    	format: 'YYYY-MM-DD HH:mm'
  	});
  	
  });
	
</script>




