<?php

	/**
	 * Adds metatags to load Javascript required for the profile
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 */

	/*
	 * <script type="text/javascript" src="<?php echo $vars['url']; ?>pg/iconjs/profile.js" ></script>
	 */

?>

	<?php if ($owner = page_owner_entity()) { ?><link rel="meta" type="application/rdf+xml" title="FOAF" href="<?php echo full_url(); ?>?view=foaf" /><?php } ?>
	
