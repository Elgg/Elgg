<?php
	/**
	 * Elgg invite page
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @link http://elgg.org/
	 */
	echo elgg_view('input/form', array(
			'action' => $vars['url'] . 'action/invitefriends/invite',
			'body' => elgg_view('invitefriends/formitems'),
			'method' => 'post'
		)
	);

?>
