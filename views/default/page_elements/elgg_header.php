<?php
/**
 * Elgg header contents
 * This file holds the header output that a user will see
 **/

?>
<div id="elgg_header">
	<div id="elgg_header_contents">
		<!-- display site name -->
		<h1><a href="<?php echo $vars['url']; ?>"><?php echo $vars['config']->sitename; ?></a></h1>
		<?php
			// insert site-wide navigation
			echo elgg_view('navigation/site_nav');
			// insert a view which can be extended
			echo elgg_view('header/extend');
		?>
	</div>
</div>