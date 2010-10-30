<?php
/**
 * Adds metatags to load Javascript required for the profile
 * 
 * @package ElggProfile
 * 
 */

if ($owner = elgg_get_page_owner()) { ?>
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="<?php echo full_url(); ?>?view=foaf" />
<?php } ?>