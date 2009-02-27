<?php

	/**
	 * Elgg exception
	 * Displays a single exception
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An exception
	 */

	global $CONFIG;
	 
	$class = get_class($vars['object']);
	$message = autop($vars['object']->getMessage());
	
	$body = <<< END
	<p class="messages-exception">
		<span title="$class">
			<b>$message</b>
		</span>
	</p>
END;

	if ($CONFIG->debug)
	{
		$details = autop(htmlentities(print_r($vars['object'], true), ENT_QUOTES, 'UTF-8'));
		$body .= <<< END
		<hr />
		<p class="messages-exception-detail">
			$details
		</p>
END;
	}
	
	$title = $class;
	
	echo elgg_view_layout("one_column", elgg_view_title($title) . $body);
?>