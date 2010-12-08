<?php
/**
 * Elgg header contents
 * This file holds the header output that a user will see
 **/

?>
<div class="elgg-page-header">
	<div class="elgg-page-header-inner elgg-center elgg-width-classic">
		<?php
			// link back to main site.
			echo elgg_view('page/elements/header_logo', $vars);
			
			// drop-down login
			echo elgg_view('account/login-dropdown');
		
			// insert site-wide navigation
			echo elgg_view('navigation/site_nav');

			// insert a view which can be extended
			echo elgg_view('header/extend');
		?>
	</div>
</div>
