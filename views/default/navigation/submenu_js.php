<?php
/**
 * Javascript to expand submenu items.
 *
 * @package Elgg
 * @subpackage Core
 */
?>

<script type="text/javascript">
$(document).ready(function() {
	$('.submenu span.child_indicator').click(function() {
		var submenu = $(this).parent().parent().find('ul.submenu.child:first');
		var closeChild = $($(this).find('.close_child'));
		var openChild = $($(this).find('.open_child'));

		if (submenu.is(':visible')) {
			closeChild.addClass('hidden');
			openChild.removeClass('hidden');
		} else {
			closeChild.removeClass('hidden');
			openChild.addClass('hidden');
		}

		submenu.slideToggle();
		return false;
	});
});
</script>