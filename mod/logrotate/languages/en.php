<?php
/**
 * Elgg log rotator language pack.
 *
 * @package ElggLogRotate
 */

$english = array(
	'logrotate:period' => 'How often should the system log be archived?',

	'logrotate:weekly' => 'Once a week',
	'logrotate:monthly' => 'Once a month',
	'logrotate:yearly' => 'Once a year',

	'logrotate:logrotated' => "Log rotated\n",
	'logrotate:lognotrotated' => "Error rotating log\n",
	
	'logrotate:date' => 'Delete archived logs older than a',

	'logrotate:week' => 'week',
	'logrotate:month' => 'month',
	'logrotate:year' => 'year',
		
	'logrotate:logdeleted' => "Log deleted\n",
	'logrotate:lognotdeleted' => "Error deleting log\n",
);

add_translation("en", $english);
