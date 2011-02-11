<?php

global $NOTIFICATION_HANDLERS;

?> 
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">

$(document).ready(function () {
<?php 
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
?>
	$('input[type=checkbox]:checked').parent("a.<?php echo $method; ?>toggleOff").each(function(){
		$(this).removeClass('<?php echo $method; ?>toggleOff').addClass('<?php echo $method; ?>toggleOn');
	});
	
<?php
}
?>

});

	clickflag = 0;

<?php 
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
?>
function adjust<?php echo $method; ?>(linkId) {
	var obj = $(this).prev("a");
	
	if (obj.className == "<?php echo $method; ?>toggleOff") {
		obj.className = "<?php echo $method; ?>toggleOn";
	} else {
		obj.className = "<?php echo $method; ?>toggleOff";
	}
	return false;
}
function adjust<?php echo $method; ?>_alt(linkId) {
	var obj = document.getElementById(linkId);
	
	if (obj.className == "<?php echo $method; ?>toggleOff") {
		obj.className = "<?php echo $method; ?>toggleOn";
		$('#' + linkId).children("input[type='checkbox']").attr('checked', true);
	} else {
		obj.className = "<?php echo $method; ?>toggleOff";
		$('#' + linkId).children("input[type='checkbox']").attr('checked', false);
	}
	return false;
}
<?php
}
?>

</script>