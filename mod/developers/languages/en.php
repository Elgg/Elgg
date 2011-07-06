<?php
/**
 * Elgg developer tools English language file.
 *
 */

$english = array(
	// menu
	'admin:developers' => 'Developers',
	'admin:developers:settings' => 'Developer Settings',
	'admin:developers:preview' => 'Theming Preview',
	'admin:developers:inspect' => 'Inspect',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Control your development and debugging settings below. Some of these settings are also available on other admin pages.',
	'developers:label:simple_cache' => 'Use simple cache',
	'developers:help:simple_cache' => 'Turn off the file cache when developing. Otherwise, changes to your views (including css) will be ignored.',
	'developers:label:view_path_cache' => 'Use view path cache',
	'developers:help:view_path_cache' => 'Turn this off when developing. Otherwise, new views in your plugins will not be registered.',
	'developers:label:debug_level' => "Trace level",
	'developers:help:debug_level' => "This controls the amount of information logged. See elgg_log() for more information.",
	'developers:label:display_errors' => 'Display fatal PHP errors',
	'developers:help:display_errors' => "By default, Elgg's .htaccess file supresses the display of fatal errors.",
	'developers:label:screen_log' => "Log to the screen",
	'developers:help:screen_log' => "This displays elgg_log() and elgg_dump() output on the web page.",
	'developers:label:show_strings' => "Show raw translation strings",
	'developers:help:show_strings' => "This displays the translation strings used by elgg_echo().",
	'developers:label:wrap_views' => "Wrap views",
	'developers:help:wrap_views' => "This wraps almost every view with HTML comments. Useful for finding the view creating particular HTML.",
	'developers:label:log_events' => "Log events and plugin hooks",
	'developers:help:log_events' => "Write events and plugin hooks to the log. Warning: there are many of these per page.",

	'developers:debug:off' => 'Off',
	'developers:debug:error' => 'Error',
	'developers:debug:warning' => 'Warning',
	'developers:debug:notice' => 'Notice',
	
	// inspection
	'developers:inspect:help' => 'Inspect configuration of the Elgg framework.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' in %s",

	// theme preview
	'theme_preview:general' => 'Introduction',
	'theme_preview:breakout' => 'Break out of iframe',
	'theme_preview:buttons' => 'Buttons',
	'theme_preview:components' => 'Components',
	'theme_preview:forms' => 'Forms',
	'theme_preview:grid' => 'Grid',
	'theme_preview:icons' => 'Icons',
	'theme_preview:modules' => 'Modules',
	'theme_preview:navigation' => 'Navigation',
	'theme_preview:typography' => 'Typography',

	// status messages
	'developers:settings:success' => 'Settings saved',
);

add_translation('en', $english);
