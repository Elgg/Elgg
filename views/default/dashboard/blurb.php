<?php
/**
 * Elgg comments add form
 *
 * @package Elgg
 * @author Curverider Ltd <info@elgg.com>
 * @link http://elgg.com/
 *
 * @uses $vars['entity']
 */
?>

<div id="dashboard_info">
<p>
<?php

	echo elgg_echo("dashboard:nowidgets");

?>
</p>
<p>
	<a href="<?php echo $vars['url']; ?>dashboard/latest.php"><?php echo elgg_echo('content:latest:blurb'); ?></a>
</p>
</div>