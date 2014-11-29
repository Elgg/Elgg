<?php
/**
 * Aalborg theme navbar
 *
 */

// drop-down login
echo elgg_view('core/account/login_dropdown');

?>

<a class="elgg-button-nav" rel="toggle" href=".elgg-nav-collapse">
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</a>

<div class="elgg-nav-collapse">
	<?php echo elgg_view_menu('site'); ?>
</div>
