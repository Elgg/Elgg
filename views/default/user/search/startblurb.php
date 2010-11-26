<?php
/**
 * @package Elgg
 * @subpackage Core
 * @deprecated 1.7
 */

elgg_deprecated_notice('view user/search/startblurb was deprecated.', 1.7);

?>
<div class="contentWrapper">
	<?php

		echo sprintf(elgg_echo("user:search:startblurb"),$vars['tag']);

	?>
</div>
