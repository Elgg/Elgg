<?php
/**
 * Elgg htmLawed tag filtering.
 *
 * @package ElgghtmLawed
 */

/**
 * Initialise plugin
 *
 */
function htmlawed_init() {
	elgg_register_plugin_hook_handler('validate', 'input', 'htmlawed_filter_tags', 1);
}

/**
 * Hooked for all elements in htmlawed.
 * Used to filter out style attributes we don't want.
 *
 * @param $element
 * @param $attribute_array
 * @return unknown_type
 */
function htmlawed_hook($element, $attribute_array) {
	// these are the default styles used by tinymce.
	$allowed_styles = array(
		'color', 'cursor', 'text-align', 'vertical-align', 'font-size',
		'font-weight', 'font-style', 'border', 'border-top', 'background-color',
		'border-bottom', 'border-left', 'border-right',
		'margin', 'margin-top', 'margin-bottom', 'margin-left',
		'margin-right',	'padding', 'float', 'text-decoration'
	);
	
	$allowed_styles = elgg_trigger_plugin_hook('allowed_styles', 'htmlawed', NULL, $allowed_styles);

	// must return something.
	$string = '';

	foreach ($attribute_array as $attr => $value) {
		if ($attr == 'style') {
			$styles = explode(';', $value);

			$style_str = '';
			foreach ($styles as $style) {
				if (!trim($style)) {
					continue;
				}
				list($style_attr, $style_value) = explode(':', trim($style));
				$style_attr = trim($style_attr);
				$style_value = trim($style_value);

				if (in_array($style_attr, $allowed_styles)) {
					$style_str .= "$style_attr: $style_value; ";
				}
			}

			if ($style_str) {
				$string .= " style=\"$style_str\"";
			}

		} else {
			$string .= " $attr=\"$value\"";
		}
	}

	// some things (tinymce) don't like tags like <p > so make sure
	// to only add a space if needed.
	if ($string = trim($string)) {
		$string = " $string";
	}

	$r = "<$element$string>";
	return $r;
}

/**
 * htmLawed filtering of tags, called on a plugin hook
 *
 * @param mixed $var Variable to filter
 * @return mixed
 */
function htmlawed_filter_tags($hook, $entity_type, $returnvalue, $params) {
	$return = $returnvalue;
	$var = $returnvalue;

	if (include_once(dirname(__FILE__) . "/vendors/htmLawed/htmLawed.php")) {

		$htmlawed_config = array(
			// seems to handle about everything we need.
			'safe' => true,
			'deny_attribute' => 'class, on*',
			'hook_tag' => 'htmlawed_hook',
	
			'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto'
				// apparent this doesn't work.
				//. 'style:color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
		);
		
		$htmlawed_config = elgg_trigger_plugin_hook('config', 'htmlawed', NULL, $htmlawed_config);

		if (!is_array($var)) {
			$return = "";
			$return = htmLawed($var, $htmlawed_config);
		} else {

			array_walk_recursive($var, 'htmLawedArray', $htmlawed_config);

			$return = $var;
		}
	}

	return $return;
}

/**
 * wrapper function for htmlawed for handling arrays
 */
function htmLawedArray(&$v, $k, $htmlawed_config) {
	$v = htmLawed($v, $htmlawed_config);
}



elgg_register_event_handler('init', 'system', 'htmlawed_init');
