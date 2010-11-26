<?php

    /**
	 * Elgg twitter edit page
	 *
	 * @package ElggTwitter
	 */

?>
	<p>
		<?php echo elgg_echo("twitter:username"); ?>
		<input type="text" name="params[twitter_username]" value="<?php echo htmlentities($vars['entity']->twitter_username); ?>" />	
		<br /><?php echo elgg_echo("twitter:num"); ?>
		<input type="text" name="params[twitter_num]" value="<?php echo htmlentities($vars['entity']->twitter_num); ?>" />	
	
	</p>