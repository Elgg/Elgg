<?php

namespace Elgg\Input;

/**
 * Validates input using htmlawed
 *
 * @since 4.0
 */
class ValidateInputHandler {
	
	/**
	 * htmLawed filtering of data
	 *
	 * Called on the 'sanitize', 'input' event
	 *
	 * htmLawed's $config argument is filtered by the [config, htmlawed] event.
	 * htmLawed's $spec argument is filtered by the [spec, htmlawed] event.
	 *
	 * For information on these arguments, see
	 * http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s2.2
	 *
	 * @param \Elgg\Event $event 'sanitize', 'input'
	 *
	 * @return mixed
	 */
	public function __invoke(\Elgg\Event $event) {
		$var = $event->getValue();
		if ((!is_string($var) && !is_array($var)) || empty($var)) {
			return $var;
		}
	
		$config = [
			// seems to handle about everything we need.
			'safe' => true,
	
			// remove comments/CDATA instead of converting to text
			'comment' => 1,
			'cdata' => 1,
			
			// do not check for unique ids as the full input stack could be checked multiple times
			// @see https://github.com/Elgg/Elgg/issues/12934
			'unique_ids' => 0,
	
			'elements' => '*-applet-button-form-input-textarea-iframe-script-style-embed-object',
			'deny_attribute' => 'class, on*, formaction',
			'hook_tag' => '_elgg_htmlawed_tag_post_processor',
	
			'schemes' => '*:http,https,ftp,news,mailto,rtsp,teamspeak,gopher,mms,callto',
		];
	
		// add nofollow to all links on output
		if (!elgg_in_context('input')) {
			$config['anti_link_spam'] = ['/./', ''];
		}
	
		$config = elgg_trigger_event_results('config', 'htmlawed', [], $config);
		$spec = elgg_trigger_event_results('spec', 'htmlawed', [], '');
	
		if (!is_array($var)) {
			return \Htmlawed::filter($var, $config, $spec);
		}
		
		$callback = function (&$v, $k, $config_spec) {
			if (!is_string($v) || empty($v)) {
				return;
			}
			
			list ($config, $spec) = $config_spec;
			$v = \Htmlawed::filter($v, $config, $spec);
		};
		
		array_walk_recursive($var, $callback, [$config, $spec]);
		
		return $var;
	}
	
	/**
	 * Sanitizes style attribute
	 *
	 * This function triggers the 'allowed_styles', 'htmlawed' event
	 *
	 * @param \Elgg\Event $event 'attributes', 'htmlawed'
	 *
	 * @return void|array
	 */
	public static function sanitizeStyles(\Elgg\Event $event) {
		$attributes = $event->getValue();
		$style = elgg_extract('style', $attributes);
		if (empty($style)) {
			return;
		}
		
		$allowed_styles = [
			'color', 'cursor', 'text-align', 'vertical-align', 'font-size',
			'font-weight', 'font-style', 'border', 'border-top', 'background-color',
			'border-bottom', 'border-left', 'border-right',
			'margin', 'margin-top', 'margin-bottom', 'margin-left',
			'margin-right',	'padding', 'float', 'text-decoration',
		];
		
		$allowed_styles = elgg_trigger_event_results('allowed_styles', 'htmlawed', ['tag' => $event->getParam('tag')], $allowed_styles);
		
		$styles = explode(';', $style);
		
		$style_str = '';
		foreach ($styles as $style) {
			if (!trim($style) || !str_contains($style, ':')) {
				continue;
			}
			
			list($style_attr, $style_value) = explode(':', trim($style));
			$style_attr = trim($style_attr);
			$style_value = trim($style_value);
			
			if (in_array($style_attr, $allowed_styles)) {
				$style_str .= "{$style_attr}: {$style_value}; ";
			}
		}
		
		if (empty($style_str)) {
			unset($attributes['style']);
		} else {
			$attributes['style'] = trim($style_str);
		}
		
		return $attributes;
	}
}
