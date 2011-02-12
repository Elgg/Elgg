<?php

function minify_init()
{
	//make sure this runs after everyone else is done
	register_plugin_hook('display', 'view', 'minify_views', 1000);
}

function minify_views($hook, $type, $content, $params)
{
	$view = $params['view'];
	
	if (preg_match("/^js\//", $view)) {
		if (include_once dirname(__FILE__).'/lib/min/lib/JSMin.php') {
			return JSMin::minify($content);
		}
	} elseif ($view === 'css') {
		if (include_once('lib/min/lib/CSS.php')) {
			return Minify_CSS::minify($content);
		}
	}
}

register_elgg_event_handler('init', 'system', 'minify_init');

?>