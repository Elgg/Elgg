<?php
return array(
	'logrotate:period' => 'How often should the system log be archived?',

	'logrotate:logrotated' => "Log rotated\n",
	'logrotate:lognotrotated' => "Error rotating log\n",
	'logrotate:table_crashed' => "The MySQL table %s has crashed. Check the error_log and MySQL log for details.",
	'logrotate:table_crashed:subject' => "A MySQL table has crashed on %s",
	
	'logrotate:delete' => 'Delete archived logs older than a',

	'logrotate:week' => 'week',
	'logrotate:month' => 'month',
	'logrotate:year' => 'year',
	'logrotate:never' => 'never',
		
	'logrotate:logdeleted' => "Log deleted\n",
	'logrotate:lognotdeleted' => "No logs deleted\n",
);
