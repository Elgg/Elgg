<?php
return array(
	// menu
	'admin:develop_tools' => 'Tools',
	'admin:develop_tools:sandbox' => 'Theme Sandbox',
	'admin:develop_tools:inspect' => 'Inspect',
	'admin:develop_tools:unit_tests' => 'Unit Tests',
	'admin:developers' => 'Developers',
	'admin:developers:settings' => 'Settings',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Control your development and debugging settings below. Some of these settings are also available on other admin pages.',
	'developers:label:simple_cache' => 'Use simple cache',
	'developers:help:simple_cache' => 'Turn off this cache when developing. Otherwise, changes to your CSS and JavaScript will be ignored.',
	'developers:label:system_cache' => 'Use system cache',
	'developers:help:system_cache' => 'Turn this off when developing. Otherwise, changes in your plugins will not be registered.',
	'developers:label:debug_level' => "Trace level",
	'developers:help:debug_level' => "This controls the amount of information logged. See elgg_log() for more information.",
	'developers:label:display_errors' => 'Display fatal PHP errors',
	'developers:help:display_errors' => "By default, Elgg's .htaccess file supresses the display of fatal errors.",
	'developers:label:screen_log' => "Log to the screen",
	'developers:help:screen_log' => "This displays elgg_log() and elgg_dump() output on the web page.",
	'developers:label:show_strings' => "Show raw translation strings",
	'developers:help:show_strings' => "This displays the translation strings used by elgg_echo().",
	'developers:label:wrap_views' => "Wrap views",
	'developers:help:wrap_views' => "This wraps almost every view with HTML comments. Useful for finding the view creating particular HTML.
									This can break non-HTML views in the default viewtype. See developers_wrap_views() for details.",
	'developers:label:log_events' => "Log events and plugin hooks",
	'developers:help:log_events' => "Write events and plugin hooks to the log. Warning: there are many of these per page.",

	'developers:debug:off' => 'Off',
	'developers:debug:error' => 'Error',
	'developers:debug:warning' => 'Warning',
	'developers:debug:notice' => 'Notice',
	'developers:debug:info' => 'Info',

	// inspection
	'developers:inspect:help' => 'Inspect configuration of the Elgg framework.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' in %s",

	// theme sandbox
	'theme_sandbox:intro' => 'Introduction',
	'theme_sandbox:breakout' => 'Break out of iframe',
	'theme_sandbox:buttons' => 'Buttons',
	'theme_sandbox:components' => 'Components',
	'theme_sandbox:forms' => 'Forms',
	'theme_sandbox:grid' => 'Grid',
	'theme_sandbox:icons' => 'Icons',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Layouts',
	'theme_sandbox:modules' => 'Modules',
	'theme_sandbox:navigation' => 'Navigation',
	'theme_sandbox:typography' => 'Typography',

	'theme_sandbox:icons:blurb' => 'Use <em>elgg_view_icon($name)</em> or the class elgg-icon-$name to display icons.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg has unit and integration tests for detecting bugs in its core classes and functions.',
	'developers:unit_tests:warning' => 'Warning: Do Not Run These Tests on a Production Site. They can corrupt your database.',
	'developers:unit_tests:run' => 'Run',

	// status messages
	'developers:settings:success' => 'Settings saved',
);
