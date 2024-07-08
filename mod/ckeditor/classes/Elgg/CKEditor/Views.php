<?php

namespace Elgg\CKEditor;

/**
 * Event callbacks for views
 *
 * @since 5.0
 * @internal
 */
class Views {

	/**
	 * Adds editor type information and an ID to the view vars if not set
	 *
	 * @param \Elgg\Event $event 'view_vars', 'input/longtext'
	 *
	 * @return array
	 */
	public static function setInputLongTextIDViewVar(\Elgg\Event $event) {
		$vars = $event->getValue();
		
		$vars['data-editor-type'] = elgg_extract('editor_type', $vars);
		
		if (elgg_extract('id', $vars) === null) {
			// input/longtext view vars need to contain an id for editors to be initialized
			// random id generator is the same as in input/longtext
			$vars['id'] = 'elgg-input-' . base_convert(mt_rand(), 10, 36);
		}
		
		$vars['tabindex'] = '-1';
		
		return $vars;
	}

	/**
	 * Adds class to output/longtext so ckeditor styling can apply
	 *
	 * @param \Elgg\Event $event 'view_vars', 'output/longtext'
	 *
	 * @return array
	 */
	public static function setOutputLongTextClass(\Elgg\Event $event) {
		$vars = $event->getValue();
		$vars['class'] = elgg_extract_class($vars, 'ck-content');
		return $vars;
	}

	/**
	 * Sets the toolbar config if configured
	 *
	 * @param \Elgg\Event $event 'elgg.data', 'page'
	 *
	 * @return array
	 */
	public static function setToolbarConfig(\Elgg\Event $event) {
		$result = $event->getValue();
		
		$cleanup = function(string $text) {
			$buttons = explode(',', trim($text));
			
			$buttons = array_map(function($val) {
				return trim(trim($val), "'\"");
			}, $buttons);
						
			return array_values(array_filter($buttons));
		};
		
		$result['ckeditor'] = [
			'toolbar_default' => $cleanup((string) elgg_get_plugin_setting('toolbar_default', 'ckeditor')) ?: null,
			'toolbar_simple' => $cleanup((string) elgg_get_plugin_setting('toolbar_simple', 'ckeditor')) ?: null,
		];
		
		return $result;
	}

	/**
	 * Changes mention value based on config setting
	 *
	 * @param \Elgg\Event $event 'to:object', 'entity'
	 *
	 * @return array
	 */
	public static function changeToObjectForLivesearch(\Elgg\Event $event) {
		if (get_input('source') !== 'ckeditor_mentions') {
			return;
		}
		
		if (elgg_get_config('mentions_display_format') !== 'username') {
			// default name is already fine
			return;
		}
		
		$user = $event->getEntityParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$result = $event->getValue();
		$result->name = "@{$user->username}";
		
		return $result;
	}

	/**
	 * Extracts usernames from a given text. Used for notifying users.
	 *
	 * @param \Elgg\Event $event 'usernames', 'mentions'
	 *
	 * @return array
	 */
	public static function extractUsernames(\Elgg\Event $event) {
		$text = (string) $event->getParam('text');
		if (empty($text)) {
			return;
		}
		
		//data-mention="@username"
		$pattern = '/data-mention="@([^\s]+)"/mu';
		
		$matches = [];
		preg_match_all($pattern, $text, $matches);
		
		return array_merge((array) $event->getValue(), elgg_extract(1, $matches, []));
	}
	
	/**
	 * Cleanup empty paragraphs (<p>&nbsp;</p>) from longtexts
	 *
	 * @param \Elgg\Event $event 'view_vars', 'output/longtext'
	 *
	 * @return null|array
	 */
	public static function stripEmptyClosingParagraph(\Elgg\Event $event): ?array {
		
		$vars = $event->getValue();
		if (empty($vars['value'])) {
			return null;
		}
		
		$vars['value'] = preg_replace('/((\r\n|\r|\n)*<p>(&nbsp;)*<\/p>)+$/', '', trim($vars['value']));
		
		return $vars;
	}
}
