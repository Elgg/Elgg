<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:administer_utilities:logbrowser' => 'Log browser',
	'logbrowser:search' => 'Refine results',
	'logbrowser:user' => 'Username to search by',
	'logbrowser:starttime' => 'Beginning time (for example "last monday", "1 hour ago")',
	'logbrowser:endtime' => 'End time',

	'logbrowser:explore' => 'Explore log',

	'logbrowser:date' => 'Date and time',
	'logbrowser:ip_address' => 'IP address',
	'logbrowser:user:name' => 'User',
	'logbrowser:user:guid' => 'User GUID',
	'logbrowser:object' => 'Object type',
	'logbrowser:object:id' => 'Object ID',
	'logbrowser:action' => 'Action',

	'logrotate:period' => 'How often should the system log be archived?',
	'logrotate:retention' => 'Delete archived logs older x days',
	'logrotate:retention:help' => 'The number of days you wish to keep the archived logs in the database. Leave empty in order not to cleanup the archived logs.',

	'logrotate:logrotated' => "Log rotated",
	'logrotate:lognotrotated' => "Error rotating log",

	'logrotate:logdeleted' => "Log deleted",
	'logrotate:lognotdeleted' => "No logs deleted",
);
