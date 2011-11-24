<?php
/**
 * FOAF
 * 
 * @package ElggProfile
 * 
 */

$owner = elgg_get_page_owner_entity();

if (elgg_instanceof($owner, 'user')) {
?>
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="<?php echo full_url(); ?>?view=foaf" />
<?php

}