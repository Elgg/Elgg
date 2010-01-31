<?php
/**
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 * @deprecated 1.7
 */
elgg_log('view groups/search/startblurb was deprecated in 1.7', 'WARNING');
?>

<div class="contentWrapper">
	<?php
		echo sprintf(elgg_echo("group:search:startblurb"),$vars['tag']);
	?>
</div>
