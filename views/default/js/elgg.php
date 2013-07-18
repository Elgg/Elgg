if (typeof elgg != 'object') {
	throw new Error('elgg configuration object is not defined! You should include js/initialize_elgg view before JS library files!');
}
<?php
/**
 * Core Elgg javascript loader
 */
global $CONFIG;

$lib_dir = 'js/lib';
$classes_dir = 'js/classes';
$files = array(
	// these must come first
	'vendors/sprintf',
	"$lib_dir/elgglib",

	// class definitions
	"$classes_dir/ElggEntity",
	"$classes_dir/ElggUser",
	"$classes_dir/ElggPriorityList",

	//libraries
	"$lib_dir/prototypes",
	"$lib_dir/hooks",
	"$lib_dir/security",
	"$lib_dir/languages",
	"$lib_dir/ajax",
	"$lib_dir/session",
	"$lib_dir/pageowner",
	"$lib_dir/configuration",

	//ui
	"$lib_dir/ui",
	"$lib_dir/ui.widgets",
);

$root_path = elgg_get_root_path();

foreach ($files as $file) {
	readfile("{$root_path}$file.js");
	// putting a new line between the files to address http://trac.elgg.org/ticket/3081
	echo "\n";
}

/**
 * Set some values that are cacheable
 */
?>
//<script>

elgg.version = '<?php echo elgg_get_version(); ?>';
elgg.release = '<?php echo elgg_get_version(true); ?>';
elgg.config.wwwroot = '<?php echo elgg_get_site_url(); ?>';

// refresh token 3 times during its lifetime (in microseconds 1000 * 1/3)
elgg.security.interval = <?php echo (int)_elgg_services()->actions->getActionTokenTimeout() * 333; ?>;
elgg.config.language = '<?php echo (empty($CONFIG->language) ? 'en' : $CONFIG->language); ?>';

!function () {
	var languagesUrl = elgg.config.wwwroot + 'ajax/view/js/languages?language=' + elgg.get_language();
	if (elgg.config.simplecache_enabled) {
		languagesUrl += '&lc=' + elgg.config.lastcache;
	}

	define('elgg', ['jquery', languagesUrl], function($, translations) {
		elgg.add_translation(elgg.get_language(), translations);

		$(function() {
			elgg.trigger_hook('init', 'system');
			elgg.trigger_hook('ready', 'system');
		});

		return elgg;
	});
}();

require(['elgg']); // Forces the define() function to always run

<?php
$previous_content = elgg_view('js/initialise_elgg');
if ($previous_content) {
	elgg_deprecated_notice("The view 'js/initialise_elgg' has been deprecated for js/elgg", 1.8);
	echo $previous_content;
}
?>

elgg.trigger_hook('boot', 'system');
