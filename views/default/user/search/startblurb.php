<?php
/**
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @deprecated 1.7
 * @link http://elgg.org/
 */

elgg_log('view user/search/startblurb was deprecated in 1.7', 'WARNING');

?>
<div class="contentWrapper">
	<?php

		echo sprintf(elgg_echo("user:search:startblurb"),$vars['tag']);

	?>
</div>
