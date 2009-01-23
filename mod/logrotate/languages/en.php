<?php
	/**
	 * Elgg log rotator language pack.
	 * 
	 * @package ElggLogRotate
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$english = array(
		'logrotate:period' => 'How often should the system log be archived?',
	
		'logrotate:weekly' => 'Once a week',
		'logrotate:monthly' => 'Once a month',
		'logrotate:yearly' => 'Once a year',
	
		'logrotate:logrotated' => "Log rotated\n",
		'logrotate:lognotrotated' => "Error rotating log\n",
	);
					
	add_translation("en",$english);
?>