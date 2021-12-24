<?php

$touch_punch = elgg_view('jquery.ui.touch-punch.js');
if (substr($touch_punch, -9) === '(jQuery);') {
	$touch_punch = substr($touch_punch, 0, -9);
}

?>
/*
 * AMD Wrapper for jquery-ui-touch-punch
 */
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		// AMD. Register as an anonymous module.
		define([
			'jquery',
			'jquery-ui/widgets/draggable'
		], factory );
	} else {
		// Browser globals
		factory( jQuery );
	}
}<?php echo $touch_punch; ?>);
