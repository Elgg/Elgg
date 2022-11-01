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
			// apparent this doesn't work.
			// 'style:color,cursor,text-align,font-size,font-weight,font-style,border,margin,padding,float'
		];
	
		// add nofollow to all links on output
		if (!elgg_in_context('input')) {
			$config['anti_link_spam'] = ['/./', ''];
		}
	
		$config = elgg_trigger_event_results('config', 'htmlawed', [], $config);
		$spec = elgg_trigger_event_results('spec', 'htmlawed', [], '');
	
		if (!is_array($var)) {
			return \Htmlawed::filter($var, $config, $spec);
		} else {
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
	}
}
