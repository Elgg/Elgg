<?php

    /**
	 * Elgg twitter edit page
	 *
	 * @package ElggTwitter
	 */

?>
	<p>
		<label><?php echo elgg_echo("twitter:username"); ?>
		<?php echo elgg_view('input/text', array(
			'internalname' => 'params[twitter_username]',
			'value' => $vars['entity']->twitter_username,
		)) ?>
		</label>
	</p>
	<p>
		<label><?php echo elgg_echo("twitter:num"); ?>
		<?php echo elgg_view('input/text', array(
			'internalname' => 'params[twitter_num]',
			'value' => $vars['entity']->twitter_num,
		)) ?>
		</label>
		<small><?php echo elgg_echo("twitter:apibug") ?></small>
	</p>