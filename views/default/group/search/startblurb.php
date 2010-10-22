<?php
/**
 * @package Elgg
 * @subpackage Core
 * @deprecated 1.7
 */
elgg_deprecated_notice('view groups/search/startblurb was deprecated.', 1.7);
?>

<div class="contentWrapper">
	<?php
		echo sprintf(elgg_echo("group:search:startblurb"),$vars['tag']);
	?>
</div>
