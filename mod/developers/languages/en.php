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
	'theme_preview:general' => 'General',
	'theme_preview:navigation' => 'Navigation',
	'theme_preview:forms' => 'Forms',
	'theme_preview:objects' => 'Objects',
	'theme_preview:grid' => 'Grid',
	'theme_preview:widgets' => 'Widgets',
	'theme_preview:icons' => 'Icons',
);

add_translation('en', $english);
