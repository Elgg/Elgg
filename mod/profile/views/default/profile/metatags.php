<?php

	/**
	 * Adds metatags to load Javascript required for the profile
	 * 
	 * @package ElggProfile
	 * 
	 */

	/*
	 * <script type="text/javascript" src="<?php echo $vars['url']; ?>pg/iconjs/profile.js" ></script>
	 */

?>

	<?php if ($owner = page_owner_entity()) { ?><link rel="meta" type="application/rdf+xml" title="FOAF" href="<?php echo full_url(); ?>?view=foaf" /><?php } ?>
	
