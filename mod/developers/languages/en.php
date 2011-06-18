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

	'developers:debug:off' => 'Off',
	'developers:debug:error' => 'Error',
	'developers:debug:warning' => 'Warning',
	'developers:debug:notice' => 'Notice',

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
);

add_translation('en', $english);
