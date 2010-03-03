<?php

    /**
	 * Elgg twitter edit page
	 *
	 * @package ElggTwitter
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

?>
	<p>
		<?php echo elgg_echo("twitter:username"); ?>
		<input type="text" name="params[twitter_username]" value="<?php echo htmlentities($vars['entity']->twitter_username); ?>" />	
		<br /><?php echo elgg_echo("twitter:num"); ?>
		<input type="text" name="params[twitter_num]" value="<?php echo htmlentities($vars['entity']->twitter_num); ?>" />	
	
	</p>