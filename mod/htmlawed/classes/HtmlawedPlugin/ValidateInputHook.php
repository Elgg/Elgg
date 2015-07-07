<?php
namespace HtmlawedPlugin;

/**
 * htmLawed filtering of data
 *
 * Called on the 'validate', 'input' plugin hook
 *
 * Triggers the 'config', 'htmlawed' plugin hook so that plugins can change
 * htmlawed's configuration. For information on configuraton options, see
 * http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s2.2
 */
class ValidateInputHook {

	const KLASS = __CLASS__;

	/**
	 * @param string $hook   Hook name
	 * @param string $type   The type of hook
	 * @param mixed  $result Data to filter
	 * @param array  $params Not used
	 * @return mixed
	 */
	public function __invoke($hook, $type, $result, $params = null) {
		$var = $result;

		elgg_load_library('htmlawed');

		$htmlawed_config = array(
			// seems to handle about everything we need.
			'safe' => true,

			// remove comments/CDATA instead of converting to text
			'comment' => 1,
			'cdata' => 1,

			'deny_attribute' => 'class, on*',
			'hook_tag' => 'HtmlawedPlugin\\postProcessorTag', // Htmlawed demands this is be a function :(

			'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto',
			// apparent this doesn't work.
			// 'style:color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
		);

		// add nofollow to all links on output
		if (!elgg_in_context('input')) {
			$htmlawed_config['anti_link_spam'] = array('/./', '');
		}

		$htmlawed_config = elgg_trigger_plugin_hook('config', 'htmlawed', null, $htmlawed_config);

		if (!is_array($var)) {
			$result = htmLawed($var, $htmlawed_config);
		} else {
			array_walk_recursive($var, [__CLASS__, 'htmLawedArray'], $htmlawed_config);
			$result = $var;
		}

		return $result;
	}

	/**
	 * wrapper function for htmlawed for handling arrays
	 */
	public static function htmLawedArray(&$v, $k, $htmlawed_config) {
		$v = htmLawed($v, $htmlawed_config);
	}
}

/**
 * Post processor for tags in htmlawed
 *
 * Htmlawed demands this is be a function :(
 *
 * This runs after htmlawed has filtered. It runs for each tag and filters out
 * style attributes we don't want.
 *
 * This function triggers the 'allowed_styles', 'htmlawed' plugin hook.
 *
 * @param string $element    The tag element name
 * @param array  $attributes An array of attributes
 * @return string
 * @access private
 */
function postProcessorTag($element, $attributes = false) {
	if ($attributes === false) {
		// This is a closing tag. Prevent further processing to avoid inserting a duplicate tag
		return "</${element}>";
	}

	// this list should be coordinated with the WYSIWYG editor used (tinymce, ckeditor, etc.)
	$allowed_styles = array(
		'color', 'cursor', 'text-align', 'vertical-align', 'font-size',
		'font-weight', 'font-style', 'border', 'border-top', 'background-color',
		'border-bottom', 'border-left', 'border-right',
		'margin', 'margin-top', 'margin-bottom', 'margin-left',
		'margin-right',	'padding', 'float', 'text-decoration'
	);

	$params = array('tag' => $element);
	$allowed_styles = elgg_trigger_plugin_hook('allowed_styles', 'htmlawed', $params, $allowed_styles);

	// must return something.
	$string = '';

	foreach ($attributes as $attr => $value) {
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
				$style_str = trim($style_str);
				$string .= " style=\"$style_str\"";
			}

		} else {
			$string .= " $attr=\"$value\"";
		}
	}

	// Some WYSIWYG editors do not like tags like <p > so only add a space if needed.
	if ($string = trim($string)) {
		$string = " $string";
	}

	$r = "<$element$string>";
	return $r;
}
