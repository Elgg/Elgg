<?php

    /**
	 * Elgg pages widget edit
	 *
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

?>
	<p>
		<?php echo elgg_echo("pages:num"); ?>
		<input type="text" name="params[pages_num]" value="<?php echo htmlentities($vars['entity']->pages_num); ?>" />	
    </p>