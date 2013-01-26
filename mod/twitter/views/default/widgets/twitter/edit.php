<?php

/**
 * Elgg twitter edit page
 *
 * @package ElggTwitter
 */

?>
<div>
	<?php echo elgg_echo("twitter:username"); ?>
	<?php echo elgg_view('input/text', array(
		'name' => 'params[twitter_username]',
		'value' => $vars['entity']->twitter_username,
	)) ?>
</div>
<div>
	<?php echo elgg_echo("twitter:num"); ?>
	<?php echo elgg_view('input/text', array(
		'name' => 'params[twitter_num]',
		'value' => $vars['entity']->twitter_num,
	)) ?>
	<span class="elgg-text-help"><?php echo elgg_echo("twitter:apibug"); ?></span>
</div>