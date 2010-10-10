<?php
/**
 * Installer English Language
 *
 * @package ElggLanguage
 * @subpackage Installer
 */

$english = array(
	'install:title' => 'Elgg Install',
	'install:welcome' => 'Welcome',
	'install:requirements' => 'Requirements check',
	'install:database' => 'Database installation',
	'install:settings' => 'Configure site',
	'install:admin' => 'Create admin account',
	'install:complete' => 'Finished',

	'install:welcome:instructions' => "Installing Elgg has 6 simple steps and reading this welcome is the first one!

If you haven't already, read through the installation instructions included with Elgg (or click the instructions link at the bottom of the page).

If you are ready to proceed, click the Next button.",
	'install:requirements:instructions:success' => "Your server passed the requirement checks.",
	'install:requirements:instructions:failure' => "Your server failed the requirements check. After you have fixed the below issues, refresh this page.",
	'install:requirements:instructions:warning' => "Your server passed the requirements check, but there is at least one warning. We recommend that you check the install troubleshooting page for more details.",

	'install:require:php' => 'PHP',
	'install:require:htaccess' => 'Web server',
	'install:require:engine' => 'Settings file',
	'install:require:database' => 'Database',

	'install:check:root' => 'Your web server does not have permission to create an .htaccess file in the root directory of Elgg. You have two choices:

		1. Change the permissions on the root directory

		2. Copy the file htaccess_dist to .htaccess',

	'install:check:php:version' => 'Elgg requires PHP %s or above. This server is using version %s.',
	'install:check:php:extension' => 'Elgg requires the PHP extension %s.',
	'install:check:php:extension:recommend' => 'It is recommended that the PHP extension %s is installed.',
	'install:check:php:open_basedir' => 'The open_basedir PHP directive may prevent Elgg from saving files to its data directory.',
	'install:check:php:safe_mode' => 'Running PHP in safe mode is not recommened and may cause problems with Elgg.',

	'install:check:htaccess_exists' => 'There is an .htaccess file in the root directory of Elgg. Please remove it.',
	'install:check:htaccess_fail' => 'Unable to create an .htaccess file in the root directory of Elgg. You will need to copy htaccess_dist to .htaccess',
	'install:check:rewrite:success' => 'The test of the rewrite rules was successful.',
	'install:check:rewrite:fail' => 'The URL rewrite test failed.',
	'install:check:rewrite:unknown' => 'The result rewrite test could not be determined.  Continue at your own risk.',

	'install:check:enginedir' => 'Your web server does not have permission to create the settings.php file in the engine directory. You have two choices:

		1. Change the permissions on the engine directory

		2. Copy the file settings.example.php to settings.php and follow the instructions in it for setting your database parameters.',

	'install:check:php:success' => "Your server's PHP satisfies all of Elgg's requirements.",
	'install:check:database' => 'The database requirements are checked when Elgg loads its database.',

	'install:database:instructions' => "If you haven't already created a database for Elgg, do that now. Then fill in the values below to initialize the Elgg database.",
	'install:database:error' => 'There was an error creating the Elgg database and installation cannot continue. Review the message above and correct any problems. If you need more help, visit the Install troubleshooting link below or post to the Elgg community forums.',

	'install:database:label:dbuser' =>  'Database Username',
	'install:database:label:dbpassword' => 'Database Password',
	'install:database:label:dbname' => 'Database Name',
	'install:database:label:dbhost' => 'Database Host',
	'install:database:label:dbprefix' => 'Database Table Prefix',

	'install:database:help:dbuser' => 'User that has full priviledges to the MySQL database that you created for Elgg',
	'install:database:help:dbpassword' => 'Password for the above database user account',
	'install:database:help:dbname' => 'Name of the Elgg database',
	'install:database:help:dbhost' => 'Hostname of the MySQL server (usually localhost)',
	'install:database:help:dbprefix' => "The prefix given to all of Elgg's tables (usually elgg_)",

	'install:dbuser' => '',

	'install:settings:instructions' => "We need some information about the site as we configure Elgg. If you haven't created a data directory for Elgg, please do so before completing this step.",

	'install:settings:label:sitename' => 'Site Name',
	'install:settings:label:siteemail' => 'Site Email Address',
	'install:settings:label:wwwroot' => 'Site URL',
	'install:settings:label:path' => 'Elgg Install Directory',
	'install:settings:label:dataroot' => 'Data Directory',
	'install:settings:label:language' => 'Site Language',
	'install:settings:label:siteaccess' => 'Default Site Access',

	'install:settings:help:sitename' => 'The name of your new Elgg site',
	'install:settings:help:siteemail' => 'Email address used by Elgg for communication with users',
	'install:settings:help:wwwroot' => 'The address of the site (Elgg usually guesses this correctly)',
	'install:settings:help:path' => 'The directory where you put the Elgg code (Elgg usually guesses this correctly)',
	'install:settings:help:dataroot' => 'The directory that you created for Elgg to save files (the permissions on this directory are checked when you click Next)',
	'install:settings:help:language' => 'The default language for the site',
	'install:settings:help:siteaccess' => 'The default access level for new user created content',

	'install:admin:instructions' => "It is now time to create an administrator's account.",

	'install:admin:label:displayname' => 'Display Name',
	'install:admin:label:email' => 'Email Address',
	'install:admin:label:username' => 'Username',
	'install:admin:label:password1' => 'Password',
	'install:admin:label:password2' => 'Password Again',

	'install:admin:help:displayname' => 'The name that is displayed on the site for this account',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Account username used for logging in',
	'install:admin:help:password1' => "Account password must be at least %u characters long",
	'install:admin:help:password2' => 'Retype password to confirm',

	'install:admin:password:mismatch' => 'Password must match.',
	'install:admin:password:empty' => 'Password cannot be empty.',
	'install:admin:password:tooshort' => 'Your password was too short',
	'install:admin:cannot_create' => 'Unable to create an admin account.',

	'install:complete:instructions' => 'Your Elgg site is now ready to be used. Click the button below to be taken to your site.',
	'install:complete:gotosite' => 'Go to site',

	'InstallationException:UnknownStep' => '%s is an unknown installation step.',

	'install:success:database' => 'Database has been installed.',
	'install:success:settings' => 'Site settings have been saved.',
	'install:success:admin' => 'Admin account has been created.',

	'install:error:databasesettings' => 'Unable to connect to the database with these settings.',
	'install:error:oldmysql' => 'MySQL must be version 5.0 or above. Your server is using %s.',
	'install:error:nodatabase' => 'Unable to use database %s. It may not exist.',
	'install:error:tables_exist' => 'There are already Elgg tables in the database. You need to either drop those tables or restart the installer and we will attempt to use them. To restart the installer, remove \'?step=database\' from the URL in your browser\'s address bar and press Enter.',
	'install:error:readsettingsphp' => 'Unable to read engine/settings.example.php',
	'install:error:writesettingphp' => 'Unable to write engine/settings.php',
	'install:error:requiredfield' => '%s is required',
	'install:error:writedatadirectory' => 'Your data directory %s is not writable by the web server.',
	'install:error:locationdatadirectory' => 'Your data directory %s must be outside of your install path for security.',
	'install:error:emailaddress' => '%s is not a valid email address',
	'install:error:createsite' => 'Unable to create the site.',
	'install:error:loadadmin' => 'Unable to load admin user.',
	'install:error:adminaccess' => 'Unable to give new user account admin privileges.',
	'install:error:adminlogin' => 'Unable to login the new admin user automatically.',
);

add_translation("en", $english);
