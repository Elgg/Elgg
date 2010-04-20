<?php
/**
 * Elgg header contents
 * This file holds the header output that a user will see
 **/

?>
<div id="elgg_header">
	<div id="elgg_header_contents">
		<!-- site name -->
		<h1><a href="<?php echo $vars['url']; ?>"><?php
		$logo = $vars['config']->site->customlogo;
		$logo_time = $vars['config']->site->customlogo_time;
		if (empty($logo)) {
			echo "<span class='network_title'>".$vars['config']->sitename ."</span>";
		} else {
			echo "<img class='custom_logo' src=\"{$vars['url']}pg/csseditor/{$logo_time}.jpg\" alt=\"". htmlentities($vars['config']->sitename) ."\" />";	
		}
		?></a></h1>
		<?php
			// drop-down login
			echo elgg_view('account/forms/login_dropdown');
		
			// insert site-wide navigation
			echo elgg_view('navigation/site_nav');
			// insert a view which can be extended
			echo elgg_view('header/extend');
		?>
	</div>
</div>